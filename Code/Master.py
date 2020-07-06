import time
import pymysql
import requests
import pyfpgrowth
import itertools
import datetime
from threading import Thread
from flask_cors import CORS
from flask import Flask, request, jsonify, json


# MAKE CHANGES
host = "localhost"
user = "root"
pwd = ""
db_name = "BE"
data = {}
app = Flask(__name__)
CORS(app)


def rules(FreqItems, confidence):
    ec_rules = {}
    for items, support in FreqItems.items():
        if len(items) > 1:
            all_perms = list(itertools.permutations(items, len(items)))
            for lst in all_perms:
                antecedent = tuple(sorted(list(lst[:len(lst) - 1])))
                consequent = lst[-1:]
                try:
                    conf = float(FreqItems[items]/FreqItems[antecedent]*100)
                    if conf >= confidence:
                        lift = float(conf/FreqItems[consequent])
                        if lift >= 1:
                            ec_rules[antecedent] = (consequent, support, conf, lift)
                except:
                    pass
    return ec_rules


@app.route('/main', methods=['POST'])
def main():
    global data
    steps = {'Get': False, 'Validate': False, 'Assign': False, 'Implement': False, 'Rules': False}
    try:
        get_data()
        steps['Get'] = True
        print("Received Data...")
        val = validate()
        if val:
            steps['Validate'] = True
        else:
            raise Exception("Not Valid DATA")
        print("Data Validated...")

        update_increment()
        print("Increment Added...")
        assigntask()
        steps['Assign'] = True
        print("Tasks Generated...")
        start = time.time()
        implement()
        steps['Implement'] = True
        print("Patterns Generated...")
        if data.get('Incremental'):
            if data['Type'] == 2:
                data['min_support'] = data['min_support']/(1-data['factor']/100)
        if data['Approach'] == 'Horizontal' or data['Approach'] == 'Normal':
            generate_rules_consolidated()
        elif data['Approach'] == 'Vertical':
            generate_rules1()
        elif data['Approach'] == 'Hybrid':
            generate_rules_consolidated_HY1()
        steps['Rules'] = True
        print("Rules Generated...")
        print("Final Log:", steps)
        print("Execution Completed")
        print("Execution Time: ", time.time()-start)
        if all(steps.values()):
            db = pymysql.connect(host, user, pwd, db_name)
            cur = db.cursor()
            ts = time.time()
            ts = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S.%f')
            if not data.get('Incremental'):
                query = "update masterrequestdetails set masterrestime = '{0}' , status = 1" \
                        " where masterreqid = {1}".format(ts, data['Master'])
                cur.execute(query)
                db.commit()
            if True:
                query = "update increment_details set restime = '{0}' , status = 1" \
                        " where masterreqid = {1} and increment_id = {2} ".format(ts, data['Master'], data['Inc_id'])
                cur.execute(query)
                db.commit()
            cur.close()
            db.close()
        resp = jsonify({'message': 'Data Received'})
        return resp
    except Exception as e:
        db = pymysql.connect(host, user, pwd, db_name)
        cur = db.cursor()
        ts = time.time()
        ts = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S.%f')
        if not data.get('Incremental'):
            query = "update masterrequestdetails set masterrestime = '{0}' , status = 0" \
                    " where masterreqid = {1}".format(ts, data['Master'])
            cur.execute(query)
            db.commit()
        if True:
            if data['mode'] == 'All-Records':
                data['Inc_id'] = 0
            query = "update increment_details set restime = '{0}' , status = 0" \
                    " where masterreqid = {1} and increment_id = {2} ".format(ts, data['Master'], data['Inc_id'])
            cur.execute(query)
            db.commit()
        cur.close()
        db.close()
        print(steps)
        print("Exception Log:", e)
        resp = jsonify({'message': 'Data Not Received'})
        return resp


@app.route('/clientdata', methods=['POST'])
def get_data():
    global data
    data = {}
    try:
        data = request.get_json()
        resp = jsonify({'message': 'Data Received'})
        return resp
    except Exception as e:
        print(e)
        resp = jsonify({'message': 'Data Not Received'})
        return resp


def validate():
    global data
    db = pymysql.connect(host, user, pwd, db_name)
    if data.get('Incremental'):
        cur = db.cursor()
        query = "select Algorithm,Approach,Support,Confidence from masterrequestdetails " \
                "where MasterReqId = {0}".format(data['Master'])
        cur.execute(query)
        res = cur.fetchall()
        cur.close()
        data.update({'Algo': res[0][0], 'Approach': res[0][1], 'min_support': res[0][2],
                     'min_confidence': res[0][3], 'c_size': 1})
        data['table'] = data['dataset']
        if data['Type'] == 1:
            data['factor'] = 0
        db.close()
        return True
    elif data['mode'] == "All-Records":
        data['factor'] = 0
        cur = db.cursor()
        query = "select Status from masterrequestdetails where MasterReqId = {0}".format(data['Master'])
        cur.execute(query)
        res = cur.fetchall()
        cur.close()
        if len(res) > 0:
            if res[0][0]:
                return False
        try:

            s_row = data['startRow']
            e_row = data['endRow']
            data['nrows'] = e_row - s_row
            data['table'] = data['dataset']
            print(data['table'])
            if data.get('c_size') is None:
                data['c_size'] = 1
            if data['Approach'] == 'Vertical' or data['Approach'] == 'Normal':
                data['c_size'] = 1
            print(data)
            cur.close()
            db.close()
            return True
        except:
            cur.close()
            db.close()
            return False


def update_increment():
    global data
    db = pymysql.connect(host, user, pwd, db_name)
    cur = db.cursor()
    if data.get('Incremental'):
        query = "select count(*) from {0}".format(data['table'])
        cur.execute(query)
        res = cur.fetchall()
        nrows = res[0][0]
        data['nrows'] = nrows
    query = "select count(distinct TransID) from {0} where seq <= {1}".format(data['table'], data['nrows'])
    cur.execute(query)
    res = cur.fetchall()
    total_trans = res[0][0]
    inc_id = 0
    factor = 0
    type1 = 0
    if data.get('Incremental'):
        if data['Type'] == 2:                       #NEW Incremental
            factor = data['factor']
            data['min_support'] = data['min_support'] - factor*data['min_support']/100
        query = "select max(increment_id) from increment_details " \
                "where masterreqid = {0} ".format(data['Master'])
        cur.execute(query)
        res = cur.fetchall()
        inc_id = res[0][0] + 1
        type1 = int(data['Type'])
    query = "INSERT INTO increment_details(`MasterReqID`, `Increment_id`,`Dataset`, `Factor`,`Type`," \
            " `Rows`, `Transactions`) VALUES ( {0},{1},'{2}',{3},{4},{5},{6}" \
            ")".format(data['Master'], inc_id, data['table'], factor, type1, data['nrows'], total_trans)
    cur.execute(query)
    db.commit()
    cur.close()
    db.close()
    data['Inc_id'] = inc_id
    return True


@app.route('/assigntask', methods=['POST'])
def assigntask():
    global data
    db = pymysql.connect(host, user, pwd, db_name)
    cur = db.cursor()

    sup = data['min_support']
    conf = data['min_confidence']
    m_req = data['Master']
    rows = data['nrows']
    table = data['table']
    if data['Approach'] == 'Horizontal' or data['Approach'] == 'Normal':
        c_size = data['c_size']
        for i in range(c_size):
            s_row = i * rows // c_size
            e_row = (i+1) * rows // c_size
            slave_data = "{0} {1} {2} {3}".format(sup, conf, s_row, e_row)
            cur.execute("insert into slaverequestdetails(MasterReqID,Inc_id,slaveReqData) "
                        "values('{0}','{1}','{2}')".format(m_req, data['Inc_id'], slave_data))
            db.commit()

    elif data['Approach'] == 'Vertical':
        cur.execute("select distinct category_id from "
                    "{1} where seq<='{0}'".format(rows, table))     #MakeChanges
        res = cur.fetchall()
        category = [x[0] for x in res]
        s_row = 0
        e_row = rows
        for i in category:
            slave_data = "{0} {1} {2} {3} {4}".format(sup, conf, s_row, e_row, i)
            cur.execute("insert into slaverequestdetails(MasterReqID,Inc_id,slaveReqData) "
                        "values('{0}','{1}','{2}')".format(m_req, data['Inc_id'], slave_data))
            db.commit()
        print(len(category))
        cur.execute("update masterrequestdetails set Cluster= '{0}' "
                    " where masterreqid = {1}".format(len(category), data['Master']))
        db.commit()
        data['c_size'] = len(category)
    elif data['Approach'] == 'Hybrid':
        c_size = data['c_size']
        total = 0
        for i in range(c_size):

            s_row = i * rows // c_size
            e_row = (i + 1) * rows // c_size
            cur.execute("select distinct category_id from {2} "
                        "where seq>'{0}' and seq<='{1}'".format(s_row, e_row, table))  # MakeChanges
            res = cur.fetchall()
            category = [x[0] for x in res]
            for j in category:
                slave_data = "{0} {1} {2} {3} {4}".format(sup, conf, s_row, e_row, j)
                cur.execute("insert into slaverequestdetails(MasterReqID,Inc_id,slaveReqData) "
                            "values('{0}','{1}','{2}')".format(m_req, data['Inc_id'], slave_data))
            total = total + len(category)

        cur.execute("update masterrequestdetails set Cluster= '{0}' "
                    " where masterreqid = {1}".format(total, data['Master']))
        data['c_size'] = total
        db.commit()
    else:
        resp = jsonify({'message': 'No file selected for uploading'})
        resp.status_code = 400
        db.close()
        return resp

    cur.close()
    resp = jsonify({'message': 'Tasks Assigned'})
    resp.status_code = 200
    db.close()
    return resp


tasks = []
slave = []
ongoing = []
status = []


def send_request(url, p_data):
    db = pymysql.connect(host, user, pwd, db_name)
    with app.test_request_context():
        try:
            resp = requests.post(url, json=p_data)
            text = json.loads(resp.text)
            if resp.status_code == 200:
                cur = db.cursor()
                cur.execute("select SlaveID from slaveDetails where SlaveIP='{0}'".format(text['ip']))
                res = cur.fetchall()
                cur.close()
                slave.append((res[0], text['ip']))
            else:
                cur = db.cursor()
                cur.execute("select SlaveID from slaveDetails where SlaveIP='{0}'".format(text['ip']))
                res = cur.fetchall()
                cur.close()
                slave.append((res[0], text['ip']))
                tasks.append(p_data)
        except:
            tasks.append(p_data)
        finally:
            db.close()


@app.route('/implement', methods=['POST'])
def implement():
    global data, slave
    db = pymysql.connect(host, user, pwd, db_name)
    cur = db.cursor()
    # Fetch Active Slaves
    cur.execute("select slaveIP,SlaveID from slaveDetails where status='Active'")
    res = cur.fetchall()
    slave = []
    slave = [(x[1], x[0]) for x in res]
    slave_count = int(data['slavecount'])
    slave = slave[:slave_count]
    cur.execute("select slavereqdata,slaveReqID from slaverequestdetails "
                "where MasterReqID='{0}' and Inc_id = {1}".format(data['Master'], data['Inc_id']))
    res = cur.fetchall()

    global tasks
    tasks = [x[0] for x in res]
    req_id = [x[1] for x in res]

    if data['Approach'] == 'Horizontal' or data['Approach'] == 'Normal':
        for i in range(len(tasks)):
            tasks[i] = list(tasks[i].strip().split(" "))
            sup = float(tasks[i][0])
            conf = float(tasks[i][1])
            s_row = int(tasks[i][2])
            e_row = int(tasks[i][3])
            tasks[i] = {'sup': sup, 'conf': conf, 's_row': s_row, 'e_row': e_row}
            tasks[i]['req_id'] = req_id[i]
            tasks[i]['Algo'] = data['Algo']
            tasks[i]['Approach'] = data['Approach']
            tasks[i]['Master'] = data['Master']
            tasks[i]['c_size'] = data['c_size']
            tasks[i]['table'] = data['table']

    elif data['Approach'] == 'Vertical':
        for i in range(len(tasks)):
            tasks[i] = list(tasks[i].strip().split(" "))
            sup = float(tasks[i][0])
            conf = float(tasks[i][1])
            s_row = int(tasks[i][2])
            e_row = int(tasks[i][3])
            category = str(tasks[i][4])
            tasks[i] = {'sup': sup, 'conf': conf, 's_row': s_row, 'e_row': e_row, 'category': category}
            tasks[i]['req_id'] = req_id[i]
            tasks[i]['Algo'] = data['Algo']
            tasks[i]['Approach'] = data['Approach']
            tasks[i]['Master'] = data['Master']
            tasks[i]['rows'] = data['nrows']
            tasks[i]['table'] = data['table']

    elif data['Approach'] == 'Hybrid':
        for i in range(len(tasks)):
            tasks[i] = list(tasks[i].strip().split(" "))
            sup = float(tasks[i][0])
            conf = float(tasks[i][1])
            s_row = int(tasks[i][2])
            e_row = int(tasks[i][3])
            category = str(tasks[i][4])
            tasks[i] = {'sup': sup, 'conf': conf, 's_row': s_row, 'e_row': e_row, 'category': category}
            tasks[i]['req_id'] = req_id[i]
            tasks[i]['Algo'] = data['Algo']
            tasks[i]['Approach'] = data['Approach']
            tasks[i]['Master'] = data['Master']
            tasks[i]['c_size'] = data['c_size']
            tasks[i]['table'] = data['table']

    else:
        pass

    global ongoing
    global status
    cur.execute("select status from slaveRequestDetails where MasterReqID='{0}'".format(data['Master']))
    s_res = cur.fetchall()
    for i in range(len(s_res)):
        if s_res[i][0] == "Complete":
            status.append(True)
        else:
            status.append(False)

    while len(tasks) != 0 or not all(status):
        if len(slave) != 0 and len(tasks) != 0:
            slave_det = slave.pop(0)
            url = 'http://'+slave_det[1]+'/slavetask'
            task_data = tasks.pop(0)
            t1 = Thread(target=send_request, args=[url, task_data]).start()
            ongoing.append({t1: (url, task_data)})

        else:
            cur.execute("select status from slaveRequestDetails where MasterReqID='{0}'".format(data['Master']))
            s_res = cur.fetchall()
            for i in range(len(s_res)):
                if s_res[i][0] == "Complete":
                    status[i] = True
            time.sleep(0.0001)
    status = []
    resp = jsonify({'message': 'Tasks Completed'})
    cur.close()
    db.close()
    return resp


def get_pat(r_id, db):
    cur = db.cursor()
    query = "select FeqItemSet,Count,Length from slaveResponseDetails where slaveReqID='{0}'".format(r_id)
    cur.execute(query)
    res = cur.fetchall()
    patterns = {}
    for i in res:
        if i[0][-1] == ",":
            i[0] = i[0][:-1]
        query = "insert into finalmasterpatterns " \
                "values('{0}','{1}','{2}','{3}')".format(data['Master'], i[0], i[1], i[2])
        cur.execute(query)
        db.commit()
        fis = tuple(map(int, i[0].split(",")))
        patterns[fis] = i[1]
    cur.close()
    return patterns


@app.route('/generate_rules1', methods=['POST'])
def generate_rules():                                  #Works on Each Req_ID
    global data
    db = pymysql.connect(host, user, pwd, db_name)
    cur = db.cursor()
    cur.execute("select slaveReqID from slaveRequestDetails "
                "where MasterReqID='{0}'".format(data['Master']))
    res = cur.fetchall()
    req_id = [x[0] for x in res]

    total_length = 0
    total_rules = 0
    start = time.time()
    conf = data['min_confidence']
    for i in req_id:
        patterns = get_pat(i, db)
        rules_list = {}
        if data['Algo'] == 'Apriori':
            rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
        elif data['Algo'] == 'FP_Growth':
            rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
        elif data['Algo'] == 'Eclat':
            rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
        elif data['Algo'] == 'New':
            rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
        for k, v in rules_list.items():
            lhs = str(k)[1:-1]
            if lhs[-1] == ',':
                lhs = lhs[:-1]
            rhs = str(v[0])[1:-1]
            if rhs[-1] == ',':
                rhs = rhs[:-1]
            con = float(v[1])
            query = "insert into finalmasterrules " \
                    "values('{0}','{1}','{2}','{3}')".format(data['Master'], lhs, rhs, con)
            cur.execute(query)
            db.commit()
        total_length += len(patterns)
        total_rules += len(rules_list)
    print("Total Statistics:")
    print("Patterns=", total_length)
    print("Rules=", total_rules)
    print("Time=", time.time()-start)
    resp = jsonify({'message': 'Rules Generated'})
    db.commit()
    cur.close()
    db.close()
    return resp


@app.route('/generate_rules', methods=['POST'])
def generate_rules1():                                   #Works on MasterID (For Vertical)
    global data
    db = pymysql.connect(host, user, pwd, db_name)
    cur = db.cursor()
    start = time.time()

    if data.get('Incremental'):
        if data['Type'] == 2:                       #NEW Incremental
            naming()

        query = "SELECT SR.FeqItemset,SR.CF,SR.Length FROM " \
                "(SELECT FeqItemSet,sum(Count) as CF,Length,category from slaveresponsedetails " \
                "where MasterReqID = {0} GROUP by FeqItemSet) as SR " \
                "inner join (SELECT category,sum(support) as SS from category_support " \
                "where MasterReqID= {0} GROUP by category) as CS on CS.category = SR.Category " \
                "where SR.CF >= CS.SS".format(data['Master'])
        """
        query = "select sum(transactions) from increment_details where " \
                "masterreqid = {0} and Increment_id < {1}".format(data['Master'], data['Inc_id'])
        cur.execute(query)
        res = cur.fetchall()
        base = int(res[0][0]) * data['min_support']
        thrs = base - base*0.4                      #IMPORTAnt
        query = "select FeqItemSet,sum(Count) as CS,Length from slaveResponseDetails " \
                "where MasterReqID='{0}' group by FeqItemSet having CS >= {1} ".format(data['Master'], thrs)
        """
    else:
        query = "select FeqItemSet,Count,Length from slaveResponseDetails " \
                "where MasterReqID='{0}'".format(data['Master'])
    cur.execute(query)
    res = cur.fetchall()
    i_id = data['Inc_id']
    patterns = {}
    for i in res:
        if i[0][-1] == ",":
            i[0] = i[0][:-1]
        fis = tuple(map(int, i[0].split(",")))
        patterns[fis] = int(i[1])
        query = "insert into finalmasterpatterns " \
                "values('{0}','{1}','{2}','{3}','{4}','New')".format(data['Master'], i_id, i[0], int(i[1]), int(i[2]))
        cur.execute(query)
        db.commit()
    db.commit()

    rules_list = {}
    conf = data['min_confidence']
    if data['Algo'] == 'Apriori':
        rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
    elif data['Algo'] == 'FP_Growth':
        rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
    elif data['Algo'] == 'Eclat':
        rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
    elif data['Algo'] == 'New':
        rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
    for k, v in rules_list.items():
        lhs = str(k)[1:-1]
        if lhs[-1] == ',':
            lhs = lhs[:-1]
        rhs = str(v[0])[1:-1]
        if rhs[-1] == ',':
            rhs = rhs[:-1]
        con = float(v[1])
        query = "insert into finalmasterrules " \
                "values('{0}','{1}','{2}','{3}','{4}')".format(data['Master'], i_id, lhs, rhs, con)
        cur.execute(query)
        db.commit()
    print("Total Statistics:")
    print("Patterns=", len(patterns))
    print("Rules=", len(rules_list))
    print("Time=", time.time() - start)
    resp = jsonify({'message': 'Rules Generated'})
    cur.close()
    db.commit()
    db.close()
    return resp


@app.route('/generate_rules_Consolidated', methods=['POST'])
def generate_rules_consolidated():                      #Merges (For Horizontal)
    global data
    db = pymysql.connect(host, user, pwd, db_name)
    patterns = {}
    cur = db.cursor()
    start = time.time()
    #cur.execute("select count(distinct TransId) from {1} "
                #"where seq<= '{0}' ".format(data['nrows'], data['table']))                     #MAKE CHANGES
    query = "select sum(Transactions) from increment_details " \
            "where masterreqid = {0}".format(data['Master'])
    cur.execute(query)
    res = cur.fetchall()
    total_trans = int(res[0][0])
    threshold = total_trans * data['min_support']

    if data.get('Incremental'):
        if data['Type'] == 2:                       #NEW Incremental
            naming()
    cur.execute("select FeqItemSet,sum(Count) as sumC, Length from slaveResponseDetails where MasterReqID='{0}'"
                " group by FeqItemSet having sumC>='{1}'".format(data['Master'], threshold))
    res = cur.fetchall()
    i_id = data['Inc_id']
    for i in res:
        if i[0][-1] == ",":
            i[0] = i[0][:-1]
        query = "insert into finalmasterpatterns values('{0}','{1}','{2}','{3}'," \
                "'{4}','New')".format(data['Master'], i_id, i[0], i[1], i[2])
        cur.execute(query)
        db.commit()
        fis = tuple(map(int, i[0].split(",")))
        patterns[fis] = int(i[1])
    conf = float(data['min_confidence'])
    rules_list = {}
    if data['Algo'] == 'Apriori':
        rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
    elif data['Algo'] == 'FP_Growth':
        rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
    elif data['Algo'] == 'Eclat':
        rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
    elif data['Algo'] == 'New':
        rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
    for k, v in rules_list.items():
        lhs = str(k)[1:-1]
        if lhs[-1] == ',':
            lhs = lhs[:-1]
        rhs = str(v[0])[1:-1]
        if rhs[-1] == ',':
            rhs = rhs[:-1]
        con = float(v[1])
        query = "insert into finalmasterrules " \
                "values('{0}','{1}','{2}','{3}','{4}')".format(data['Master'], i_id, lhs, rhs, con)
        cur.execute(query)
        db.commit()
    print("Master_ID:", data['Master'], "\nTime:", time.time()-start)
    print("Patterns=", len(patterns))
    print("Rules=", len(rules_list))
    print("Time=", time.time() - start)
    db.commit()
    db.close()
    resp = jsonify({'message': ' Consolidated Rules Generated'})
    return resp


@app.route('/generate_rules_Consolidated_HY', methods=['POST'])
def generate_rules_consolidated_HY():               #Merges (For Hybrid)
    global data
    db = pymysql.connect(host, user, pwd, db_name)
    all_patterns = {}
    cur = db.cursor()
    start = time.time()
    cur.execute("select distinct category_id from {1} "
                "where seq<= '{0}'".format(data['nrows'], data['table']))                  #MAKE CHANGES
    res = cur.fetchall()
    category = {str(x[0]): [] for x in res}
    cur.execute("select slavereqid, slavereqdata from slaverequestdetails "
                "where MasterReqID='{0}'".format(data['Master']))
    res = cur.fetchall()
    for i in res:
        cat = str(list(i[1].split(" "))[-1])
        category[cat].append(i[0])
    conf = float(data['min_confidence'])
    i_id = data['Inc_id']
    for k, v in category.items():
        patterns = {}
        cur.execute("select count(distinct TransId) from {2} where seq<= '{0}' and "
                    "category_id = '{1}'".format(data['nrows'], k, data['table']))        #MAKE CHANGES
        res = cur.fetchall()
        cat_trans = res[0][0]
        threshold = cat_trans * data['min_support']
        err = str(tuple(v))
        if len(v) == 1:
            err = err.replace(",", "")
        cur.execute("select FeqItemSet,sum(Count) as sumC,Length from slaveResponseDetails "
                    "where MasterReqID='{0}' and slavereqid in {2} group by FeqItemSet "
                    "having sumC > {1}".format(data['Master'], threshold, err))
        res = cur.fetchall()
        for j in res:
            if j[0][-1] == ",":
                j[0] = j[0][:-1]
            query = "insert into finalmasterpatterns values('{0}','{1}','{2}','{3}'," \
                    "'{4}','New')".format(data['Master'], i_id, j[0], j[1], j[2])
            cur.execute(query)
            db.commit()
            fis = tuple(map(int, j[0].split(",")))
            patterns[fis] = int(j[1])
        all_patterns.update(patterns)
    rules_list = {}
    if data['Algo'] == 'Apriori' or data['Algo'] == 'FP_Growth':
        rules_list = pyfpgrowth.generate_association_rules(all_patterns, conf)
    elif data['Algo'] == 'Eclat':
        rules_list = pyfpgrowth.generate_association_rules(all_patterns, conf)
    elif data['Algo'] == 'New':
        rules_list = pyfpgrowth.generate_association_rules(all_patterns, conf)
    for k, v in rules_list.items():
        lhs = str(k)[1:-1]
        if lhs[-1] == ',':
            lhs = lhs[:-1]
        rhs = str(v[0])[1:-1]
        if rhs[-1] == ',':
            rhs = rhs[:-1]
        con = float(v[1])
        query = "insert into finalmasterrules " \
                "values('{0}','{1}','{2}','{3}','{4}')".format(data['Master'], i_id, lhs, rhs, con)
        cur.execute(query)
        db.commit()
    cur.close()
    db.close()
    print("Master_ID:", data['Master'], "\nTime:", time.time()-start)
    print("Patterns=", len(all_patterns))
    print("Rules=", len(rules_list))
    resp = jsonify({'message': ' Consolidated Rules Generated'})

    return resp


@app.route('/generate_rules_Consolidated_HY1', methods=['POST'])
def generate_rules_consolidated_HY1():
    global data
    db = pymysql.connect(host, user, pwd, db_name)
    all_patterns = {}
    cur = db.cursor()
    start = time.time()
    if data.get('Incremental'):
        if data['Type'] == 2:                       #NEW Incremental
            naming()

    query = "SELECT SR.FeqItemset,SR.CF,SR.Length FROM " \
            "(SELECT FeqItemSet,sum(Count) as CF,Length,category from slaveresponsedetails " \
            "where MasterReqID = {0} GROUP by FeqItemSet) as SR " \
            "inner join (SELECT category,sum(support) as SS from category_support " \
            "where MasterReqID= {0} GROUP by category) as CS on CS.category = SR.Category " \
            "where SR.CF >= CS.SS".format(data['Master'])
    cur.execute(query)
    res = cur.fetchall()
    i_id = data['Inc_id']
    patterns = {}
    for i in res:
        if i[0][-1] == ",":
            i[0] = i[0][:-1]
        fis = tuple(map(int, i[0].split(",")))
        patterns[fis] = int(i[1])
        query = "insert into finalmasterpatterns " \
                "values('{0}','{1}','{2}','{3}','{4}','New')".format(data['Master'], i_id, i[0], int(i[1]), int(i[2]))
        cur.execute(query)
        db.commit()
    db.commit()

    rules_list = {}
    conf = data['min_confidence']
    if data['Algo'] == 'Apriori':
        rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
    elif data['Algo'] == 'FP_Growth':
        rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
    elif data['Algo'] == 'Eclat':
        rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
    elif data['Algo'] == 'New':
        rules_list = pyfpgrowth.generate_association_rules(patterns, conf)
    for k, v in rules_list.items():
        lhs = str(k)[1:-1]
        if lhs[-1] == ',':
            lhs = lhs[:-1]
        rhs = str(v[0])[1:-1]
        if rhs[-1] == ',':
            rhs = rhs[:-1]
        con = float(v[1])
        query = "insert into finalmasterrules " \
                "values('{0}','{1}','{2}','{3}','{4}')".format(data['Master'], i_id, lhs, rhs, con)
        cur.execute(query)
        db.commit()
    cur.close()
    db.close()
    print("Master_ID:", data['Master'], "\nTime:", time.time()-start)
    print("Patterns=", len(patterns))
    print("Rules=", len(rules_list))
    resp = jsonify({'message': ' Consolidated Rules Generated'})

    return resp


def naming():
    global data
    db = pymysql.connect(host, user, pwd, db_name)
    cur = db.cursor()
    query1 = "select SlaveReqID from slaveRequestDetails where Masterreqid = {0} " \
             "order by SlaveReqID desc limit {1}".format(data['Master'], data['c_size'])
    cur.execute(query1)
    res = cur.fetchall()
    Req_id = [x[0] for x in res]
    query3 = "select FeqItemSet,Count,Length from finalmasterpatterns " \
             "where masterreqid = {0} and Inc_id = {1}".format(data['Master'], data['Inc_id']-1)
    cur.execute(query3)
    res = cur.fetchall()
    old_FIS = {x[0]: [x[1], x[2]] for x in res}
    err = str(tuple(Req_id))
    if len(Req_id) == 1:
        err = err.replace(",", "")
    query2 = "select FeqItemSet,Count,Length from slaveResponseDetails " \
             "where MasterReqID={0} and SlaveReqID in {1} ".format(data['Master'], err)
    cur.execute(query2)
    res = cur.fetchall()
    new_FIS = {x[0]: [x[1], x[2]] for x in res}
    normal = list(set(new_FIS.keys()) & set(old_FIS.keys()))
    trend = list(set(new_FIS.keys()) - set(old_FIS.keys()))
    lazy = list(set(old_FIS.keys()) - set(new_FIS.keys()))

    factor = data['min_support'] + data['factor'] * data['min_support'] / 100
    query = "select transactions from increment_details where " \
            "masterreqid = {0} and Increment_id = {1}".format(data['Master'], data['Inc_id'])
    cur.execute(query)
    i_tn = cur.fetchall()
    i_trans = i_tn[0][0]
    trend_thrs = i_trans * factor

    query = "select sum(transactions) from increment_details where " \
            "masterreqid = {0} and Increment_id < {1}".format(data['Master'], data['Inc_id'])
    cur.execute(query)
    res = cur.fetchall()
    base = int(res[0][0]) * data['min_support']
    print("Base:", base)

    for i in normal:
        query = "insert into fis_headers " \
                "values({0},{1},'{2}',{3},{4},'Normal')".format(data['Master'], data['Inc_id'], i,
                                                                new_FIS[i][0], new_FIS[i][1])
        cur.execute(query)
        db.commit()

    for i in trend:
        if new_FIS[i][0] >= trend_thrs:
            query = "insert into finalmasterpatterns values({0},{1},{2},{3},{4}," \
                    "'Trend')".format(data['Master'], data['Inc_id'], i, base+new_FIS[i][0], new_FIS[i][1])
            cur.execute(query)
            db.commit()
            query = "insert into fis_headers values({0},{1},'{2}',{3},{4}," \
                    "'Trend')".format(data['Master'], data['Inc_id'], i, new_FIS[i][0], new_FIS[i][1])
        else:
            query = "insert into fis_headers values({0},{1},'{2}',{3},{4}," \
                    "'Normal')".format(data['Master'], data['Inc_id'], i, new_FIS[i][0], new_FIS[i][1])
        cur.execute(query)
        db.commit()

    for i in lazy:
        query = "insert into fis_headers " \
                "values({0},{1},'{2}',{3},{4},'Lazy')".format(data['Master'], data['Inc_id'], i,
                                                              old_FIS[i][0], old_FIS[i][1])
        cur.execute(query)
        db.commit()
    cur.close()
    db.close()


if __name__ == "__main__":
    app.run(port=5000)
