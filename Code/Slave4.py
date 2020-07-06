import time
import math
import pymysql
import datetime
import pyfpgrowth
from efficient_apriori import apriori
from flask import Flask, request, jsonify

FreqItems = {}
app = Flask(__name__)

my_ip = "127.0.0.1:5004"        #MAKE CHANGES
my_id = "4"                     #MAKE CHANGES
host = "localhost"
user = "root"
pwd = ""
db_name = "BE"
table = ""


def eclat(prefix, items, dict_id, sup):     # prefix is a list, items is a list of tupples, dict_id is 0
    while items:
        i, itids = items.pop()      # i is item itids is item transcation sets
        isupp = len(itids)
        if isupp >= sup:
            FreqItems[frozenset(prefix + [i])] = isupp
            suffix = []
            for j, ojtids in items:
                jtids = itids & ojtids
                if len(jtids) >= sup:
                    suffix.append((j, jtids))
        else:
            suffix = []
        dict_id += 1
        eclat(prefix+[i], sorted(suffix, key=lambda item: len(item[1]), reverse=True), dict_id, sup)


def pre_process(res):
    transactions = [[]]
    t_id = res[0][0]
    k = 0
    for i in res:
        if t_id == i[0]:
            transactions[k].append(i[1])
        else:
            k += 1
            transactions.append([])
            transactions[k].append(i[1])
            t_id = i[0]
    return transactions


def eclat_pre_process(res):
    data = {}
    for i in res:
        if i[1] not in data.keys():
            data[i[1]] = {i[0]}
        else:
            data[i[1]].add(i[0])
    return data


def AP_H(sup, conf, s_row, e_row, r_id, m_id, c_size):
    print("Apriori Horizontal")
    db = pymysql.connect(host, user, pwd, db_name)
    start = time.time()
    query = "select transID,ItemNo from {2} " \
            "where seq>'{0}' and seq<='{1}'".format(s_row, e_row, table)           #MAKE CHANGES
    cur = db.cursor()
    cur.execute(query)
    result = cur.fetchall()
    cur.close()
    trans = pre_process(result)

    sup = sup / (1 + math.log(c_size, 10))                                  #MAKE CHANGES

    item_set, rules = apriori(trans, min_support=sup, min_confidence=conf)
    print("Rules-Generation-Time:\n(R_id)=", r_id,
          "\nPatterns", len(item_set), "Rules:", len(rules), "\nTime=", time.time() - start)
    cur = db.cursor()
    for v in item_set.values():
        for k, v1 in v.items():
            count = len(k)
            k = str(sorted(k))[1:-1]
            cur.execute("insert into slaveResponseDetails "
                        "values('{0}','{1}','{2}','{3}','{4}',-1)".format(m_id, r_id, k, v1, count))
            db.commit()
    db.commit()
    db.close()
    return True


def AP_V(sup, conf, s_row, e_row, category, r_id, m_id):
    print("Apriori Vertical")
    db = pymysql.connect(host, user, pwd, db_name)
    start = time.time()
    query = "select transID,ItemNo from {1} where category_id='{0}' " \
            "and seq > '{2}' and seq<= '{3}'".format(category, table, s_row, e_row)              #MAKE CHANGES
    cur = db.cursor()
    cur.execute(query)
    result = cur.fetchall()
    cur.close()
    trans = pre_process(result)
    insert(m_id, category, len(trans) * sup)                  #RESOLUTION
    item_set, rules = apriori(trans, min_support=sup, min_confidence=conf)
    print("Rules-Generation-Time:\n(R_id)=", r_id,
          "\nPatterns", len(item_set), "Rules:", len(rules), "\nTime=", time.time() - start)
    cur = db.cursor()
    for v in item_set.values():
        for k, v1 in v.items():
            count = len(k)
            k = str(sorted(k))[1:-1]
            cur.execute("insert into slaveResponseDetails "
                        "values('{0}','{1}','{2}','{3}','{4}',{5})".format(m_id, r_id, k, v1, count, category))
            db.commit()
    db.commit()
    db.close()
    return True


def AP_HY(sup, conf, s_row, e_row, category, r_id, m_id, c_size):
    print("Apriori Hybrid")
    db = pymysql.connect(host, user, pwd, db_name)
    start = time.time()
    query = "select transID,ItemNo from {3} where category_id='{0}' " \
            "and seq > '{1}' and seq<= '{2}'".format(category, s_row, e_row, table)    # MAKE CHANGES
    cur = db.cursor()
    cur.execute(query)
    res = cur.fetchall()
    cur.close()
    trans = pre_process(res)
    insert(m_id, category, len(trans) * sup)                  #RESOLUTION
    sup = sup / (1 + math.log(c_size, 10))                              #MAKE CHANGES

    item_set, rules = apriori(trans, min_support=sup, min_confidence=conf)
    print("Rules-Generation-Time:\n(R_id)=", r_id,
          "\nPatterns", len(item_set), "Rules:", len(rules), "\nTime=", time.time() - start)
    cur = db.cursor()
    for v in item_set.values():
        for k, v1 in v.items():
            count = len(k)
            k = str(sorted(k))[1:-1]
            cur.execute("insert into slaveResponseDetails "
                        "values('{0}','{1}','{2}','{3}','{4}',{5})".format(m_id, r_id, k, v1, count, category))
            db.commit()
    cur.close()
    db.commit()
    db.close()
    return True


def FP_H(sup, conf, s_row, e_row, r_id, m_id, c_size):
    print("FP Horizontal")
    db = pymysql.connect(host, user, pwd, db_name)
    start = time.time()
    query = "select transID,ItemNo from {2} " \
            "where seq>'{0}' and seq<='{1}'".format(s_row, e_row, table)               #MAKE CHANGES
    cur = db.cursor()
    cur.execute(query)
    result = cur.fetchall()
    cur.close()
    trans = pre_process(result)

    sup = sup * len(trans) / (1 + math.log(c_size, 10))                             #MAKE CHANGES

    patterns = pyfpgrowth.find_frequent_patterns(trans, sup)
    print("Rules-Generation-Time:\n(R_id)=", r_id,
          "\nPatterns:", len(patterns), "Rules:", "\nTime=", time.time()-start)
    cur = db.cursor()
    for k, v in patterns.items():
        count = len(k)
        k = str(sorted(k))[1:-1]
        cur.execute("insert into slaveresponsedetails "
                    "values('{0}','{1}','{2}','{3}','{4}',-1)".format(m_id, r_id, k, v, count))
        db.commit()
    db.commit()
    db.close()
    return True


def FP_V(sup, conf, s_row, e_row, category, r_id, m_id):
    print("FP Vertical")
    db = pymysql.connect(host, user, pwd, db_name)
    start = time.time()
    query = "select transID,ItemNo from {1} where category_id='{0}' " \
            "and seq > '{2}' and seq<= '{3}'".format(category, table, s_row, e_row)                  #MAKE CHANGES
    cur = db.cursor()
    cur.execute(query)
    result = cur.fetchall()
    cur.close()
    trans = pre_process(result)
    insert(m_id, category, len(trans) * sup)                  #RESOLUTION
    sup = sup * len(trans)
    patterns = pyfpgrowth.find_frequent_patterns(trans, sup)

    print("Rules-Generation-Time:\n(R_id)=", r_id,
          "\nPatterns", len(patterns), "Rules:", "\nTime=", time.time() - start)
    cur = db.cursor()
    for k, v in patterns.items():
        count = len(k)
        k = str(sorted(k))[1:-1]
        cur.execute("insert into slaveResponseDetails "
                    "values('{0}','{1}','{2}','{3}','{4}',{5})".format(m_id, r_id, k, v, count, category))
        db.commit()
    db.commit()
    db.close()
    return True


def FP_HY(sup, conf, s_row, e_row, category, r_id, m_id, c_size):
    print("FP Hybrid")
    db = pymysql.connect(host, user, pwd, db_name)
    start = time.time()
    query = "select transID,ItemNo from {3} where category_id='{0}' " \
            "and seq > '{1}' and seq<= '{2}'".format(category, s_row, e_row, table)    #MAKE CHANGES
    cur = db.cursor()
    cur.execute(query)
    res = cur.fetchall()
    cur.close()
    trans = pre_process(res)
    insert(m_id, category, len(trans) * sup)                  #RESOLUTION
    sup = sup * len(trans) / (1 + math.log(c_size, 10))        #MAKE CHANGES

    patterns = pyfpgrowth.find_frequent_patterns(trans, sup)

    print("Rules-Generation-Time:\n(R_id)=", r_id,
          "\nPatterns", len(patterns), "Rules:", "\nTime=", time.time() - start)
    cur = db.cursor()
    for k, v in patterns.items():
        count = len(k)
        k = str(sorted(k))[1:-1]
        cur.execute("insert into slaveResponseDetails "
                    "values('{0}','{1}','{2}','{3}','{4}',{5})".format(m_id, r_id, k, v, count, category))
        db.commit()
    cur.close()
    db.commit()
    db.close()
    return True


def EC_H(sup, conf, s_row, e_row, r_id, m_id, c_size):
    print("Eclat Horizontal")
    db = pymysql.connect(host, user, pwd, db_name)
    start = time.time()
    global FreqItems
    FreqItems = {}
    query = "select transID,ItemNo from {2} " \
            "where seq>'{0}' and seq<='{1}'".format(s_row, e_row, table)  # MAKE CHANGES
    cur = db.cursor()
    cur.execute(query)
    result = cur.fetchall()
    trans = eclat_pre_process(result)
    dict_id = 0
    cur.execute("select count(distinct TransId) from {2} "
                "where seq> '{0}' and seq<='{1}'".format(s_row, e_row, table))
    res = cur.fetchall()
    factor = res[0][0]

    sup = sup * factor / (1 + math.log(c_size, 10))

    eclat([], sorted(trans.items(), key=lambda item: len(item[1]), reverse=False), dict_id, sup)
    print(time.time()-start)
    for k, v in FreqItems.items():
        count = len(k)
        k = str(sorted(k))[1:-1]
        cur.execute("insert into slaveresponsedetails "
                    "values('{0}','{1}','{2}','{3}','{4}',-1)".format(m_id, r_id, k, v, count))
        db.commit()
    cur.close()
    db.commit()
    db.close()

    return True


def EC_V(sup, conf, s_row, e_row, category, r_id, m_id):
    print("Eclat Vertical")
    db = pymysql.connect(host, user, pwd, db_name)
    start = time.time()
    global FreqItems
    FreqItems = {}
    query = "select transID,ItemNo from {1} where category_id='{0}' " \
            "and seq > '{2}' and seq<= '{3}'".format(category, table, s_row, e_row)  # MAKE CHANGES
    cur = db.cursor()
    cur.execute(query)
    result = cur.fetchall()
    trans = eclat_pre_process(result)
    dict_id = 0
    cur.execute("select count(distinct TransId) from {1} "
                "where category_id = '{0}' and seq > '{2}' and seq<= '{3}'".format(category, table, s_row, e_row))
    res = cur.fetchall()
    factor = res[0][0]
    insert(m_id, category, sup * factor)  # RESOLUTION
    sup = sup * factor

    eclat([], sorted(trans.items(), key=lambda item: len(item[1]), reverse=False), dict_id, sup)
    print(time.time() - start)
    for k, v in FreqItems.items():
        count = len(k)
        k = str(sorted(k))[1:-1]
        cur.execute("insert into slaveresponsedetails "
                    "values('{0}','{1}','{2}','{3}','{4}',{5})".format(m_id, r_id, k, v, count, category))
        db.commit()
    cur.close()
    db.commit()
    db.close()

    return True


def EC_HY(sup, conf, s_row, e_row, category, r_id, m_id, c_size):
    print("Eclat Hybrid")
    db = pymysql.connect(host, user, pwd, db_name)
    start = time.time()
    global FreqItems
    FreqItems = {}
    query = "select transID,ItemNo from {3} where category_id='{0}'" \
            "and seq > '{1}' and seq<= '{2}'".format(category, s_row, e_row, table)  # MAKE CHANGES
    cur = db.cursor()
    cur.execute(query)
    result = cur.fetchall()
    trans = eclat_pre_process(result)
    dict_id = 0
    cur.execute("select count(distinct TransId) from {3} where category_id = '{0}' "
                "and seq > '{1}' and seq<= '{2}'".format(category, s_row, e_row, table))
    res = cur.fetchall()
    factor = res[0][0]
    insert(m_id, category, sup * factor)         # RESOLUTION
    sup = sup * factor / (1 + math.log(c_size, 10))

    eclat([], sorted(trans.items(), key=lambda item: len(item[1]), reverse=False), dict_id, sup)
    print(time.time() - start)
    for k, v in FreqItems.items():
        count = len(k)
        k = str(sorted(k))[1:-1]
        cur.execute("insert into slaveresponsedetails "
                    "values('{0}','{1}','{2}','{3}','{4}',{5})".format(m_id, r_id, k, v, count, category))
        db.commit()
    cur.close()
    db.commit()
    db.close()

    return True


def new_mixed_H(sup, conf, s_row, e_row, r_id, m_id, c_size):
    print("New Horizontal")
    global FreqItems
    FreqItems = {}
    start = time.time()
    db = pymysql.connect(host, user, pwd, db_name)
    query = "select count(distinct TransId) from {0} " \
            "where seq > {1} and seq <= {2}".format(table, s_row, e_row)
    cur = db.cursor()
    cur.execute(query)
    res = cur.fetchall()
    u_trans = res[0][0]

    sup = sup * u_trans / (1 + math.log(c_size, 10))                             #MAKE CHANGES

    query = "select ItemNo,count(distinct ItemNo,TransId) as countI from {0} " \
            "where seq > {1} and seq <= {2} group by ItemNo " \
            "having countI >= {3}".format(table, s_row, e_row, sup)
    cur.execute(query)
    result = cur.fetchall()
    ref_list = [x[0] for x in result]
    if ref_list:
        query = "select transID,ItemNo from {2} " \
                "where seq > {0} and seq <= {1} and " \
                "ItemNo in {3}".format(s_row, e_row, table, str(tuple(ref_list)))  # MAKE CHANGES
        cur.execute(query)
        result = cur.fetchall()
    else:
        result = []
    trans = eclat_pre_process(result)
    dict_id = 0
    eclat([], sorted(trans.items(), key=lambda item: len(item[1]), reverse=False), dict_id, sup)
    print(time.time() - start)
    for k, v in FreqItems.items():
        count = len(k)
        k = str(sorted(k))[1:-1]
        cur.execute("insert into slaveresponsedetails "
                    "values('{0}','{1}','{2}','{3}','{4}',-1)".format(m_id, r_id, k, v, count))
        db.commit()
    cur.close()
    db.commit()
    db.close()

    return True


def new_mixed_V(sup, conf, s_row, e_row, category, r_id, m_id):
    print("New Vertical")
    global FreqItems
    FreqItems = {}
    start = time.time()
    db = pymysql.connect(host, user, pwd, db_name)
    cur = db.cursor()
    cur.execute("select count(distinct TransId) from {1} where category_id = '{0}' "
                "and seq > '{2}' and seq<= '{3}'".format(category, table, s_row, e_row))
    res = cur.fetchall()
    factor = res[0][0]
    insert(m_id, category, sup * factor)        # RESOLUTION
    sup = sup * factor
    print("Test:", category, sup)
    query = "select ItemNo,count(distinct ItemNo,TransId) as countI from {0} " \
            "where seq > {1} and seq <= {2} and category_id = '{4}' group by ItemNo " \
            "having countI >= {3}".format(table, s_row, e_row, sup, category)
    cur.execute(query)
    result = cur.fetchall()
    ref_list = [x[0] for x in result]
    try:
        query = "select transID,ItemNo from {2} " \
                "where seq > {0} and seq <= {1} and category_id = '{4}' and " \
                "ItemNo in {3}".format(s_row, e_row, table, str(tuple(ref_list)), category)  # MAKE CHANGES
        cur.execute(query)
        result = cur.fetchall()
    except Exception as e:
        print(e)
        result = []
    trans = eclat_pre_process(result)
    dict_id = 0
    eclat([], sorted(trans.items(), key=lambda item: len(item[1]), reverse=False), dict_id, sup)
    print(time.time() - start)

    for k, v in FreqItems.items():
        count = len(k)
        k = str(sorted(k))[1:-1]
        cur.execute("insert into slaveresponsedetails "
                    "values('{0}','{1}','{2}','{3}','{4}',{5})".format(m_id, r_id, k, v, count, category))
        db.commit()
    cur.close()
    db.commit()
    db.close()

    return True


def new_mixed_HY(sup, conf, s_row, e_row, category, r_id, m_id, c_size):
    print("New Hybrid")
    global FreqItems
    FreqItems = {}
    start = time.time()
    db = pymysql.connect(host, user, pwd, db_name)
    cur = db.cursor()
    cur.execute("select count(distinct TransId) from {1} where category_id = '{0}' "
                "and seq > '{2}' and seq<= '{3}'".format(category, table, s_row, e_row))
    res = cur.fetchall()
    factor = res[0][0]
    insert(m_id, category, sup * factor)            # RESOLUTION
    sup = sup * factor / (1 + math.log(c_size, 10))

    query = "select ItemNo,count(distinct ItemNo,TransId) as countI from {0} " \
            "where seq > {1} and seq <= {2} and category_id = '{4}' group by ItemNo " \
            "having countI >= {3}".format(table, s_row, e_row, sup, category)
    cur.execute(query)
    result = cur.fetchall()
    ref_list = [x[0] for x in result]
    try:
        query = "select transID,ItemNo from {2} " \
                "where seq > {0} and seq <= {1} and category_id = '{4}' and " \
                "ItemNo in {3}".format(s_row, e_row, table, str(tuple(ref_list)), category)  # MAKE CHANGES
        cur.execute(query)
        result = cur.fetchall()
    except Exception as e:
        print(e)
        result = []
    trans = eclat_pre_process(result)
    dict_id = 0
    eclat([], sorted(trans.items(), key=lambda item: len(item[1]), reverse=False), dict_id, sup)
    print(time.time() - start)
    for k, v in FreqItems.items():
        count = len(k)
        k = str(sorted(k))[1:-1]
        cur.execute("insert into slaveresponsedetails "
                    "values('{0}','{1}','{2}','{3}','{4}',{5})".format(m_id, r_id, k, v, count, category))
        db.commit()
    cur.close()
    db.commit()
    db.close()

    return True


def insert(mid, cat, sup):
    db = pymysql.connect(host, user, pwd, db_name)
    query = "insert into category_support values({0},{1},{2})".format(mid, cat, math.ceil(sup))  # MAKE CHANGES
    cur = db.cursor()
    cur.execute(query)
    db.commit()
    cur.close()
    db.close()


@app.route("/slavetask", methods=["POST"])
def task():
    global FreqItems
    FreqItems = {}
    db = pymysql.connect(host, user, pwd, db_name)
    comp = False
    my_task = request.get_json()
    global table
    table = my_task['table']
    ts = time.time()
    ts = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S.%f')
    cur = db.cursor()
    query = "update slaverequestdetails set slaveid='{0}', slavereqtimestamp='{1}', status='{2}' " \
            "where slaveReqID='{3}'".format(my_id, ts, "Progress",  my_task['req_id'])
    cur.execute(query)
    db.commit()
    cur.close()
    db.close()
    if (my_task['Approach'] == 'Horizontal' or my_task['Approach'] == 'Normal') and my_task['Algo'] == 'FP_Growth':
        comp = FP_H(my_task['sup'], my_task['conf'], my_task['s_row'],
                    my_task['e_row'],  my_task['req_id'], my_task['Master'], my_task['c_size'])
    elif my_task['Approach'] == 'Vertical' and my_task['Algo'] == 'FP_Growth':
        comp = FP_V(my_task['sup'], my_task['conf'], my_task['s_row'], my_task['e_row'], my_task['category'],
                    my_task['req_id'], my_task['Master'])
    elif my_task['Approach'] == 'Hybrid' and my_task['Algo'] == 'FP_Growth':
        comp = FP_HY(my_task['sup'], my_task['conf'], my_task['s_row'], my_task['e_row'], my_task['category'],
                     my_task['req_id'], my_task['Master'], my_task['c_size'])
    elif (my_task['Approach'] == 'Horizontal' or my_task['Approach'] == 'Normal') and my_task['Algo'] == 'Apriori':
        comp = AP_H(my_task['sup'], my_task['conf'], my_task['s_row'],
                    my_task['e_row'],  my_task['req_id'], my_task['Master'], my_task['c_size'])
    elif my_task['Approach'] == 'Vertical' and my_task['Algo'] == 'Apriori':
        comp = AP_V(my_task['sup'], my_task['conf'], my_task['s_row'], my_task['e_row'], my_task['category'],
                    my_task['req_id'], my_task['Master'])
    elif my_task['Approach'] == 'Hybrid' and my_task['Algo'] == 'Apriori':
        comp = AP_HY(my_task['sup'], my_task['conf'], my_task['s_row'], my_task['e_row'], my_task['category'],
                     my_task['req_id'], my_task['Master'], my_task['c_size'])
    elif (my_task['Approach'] == 'Horizontal' or my_task['Approach'] == 'Normal') and my_task['Algo'] == 'Eclat':
        comp = EC_H(my_task['sup'], my_task['conf'], my_task['s_row'],
                    my_task['e_row'], my_task['req_id'], my_task['Master'], my_task['c_size'])
    elif my_task['Approach'] == 'Vertical' and my_task['Algo'] == 'Eclat':
        comp = EC_V(my_task['sup'], my_task['conf'], my_task['s_row'], my_task['e_row'], my_task['category'],
                    my_task['req_id'], my_task['Master'])
    elif my_task['Approach'] == 'Hybrid' and my_task['Algo'] == 'Eclat':
        comp = EC_HY(my_task['sup'], my_task['conf'], my_task['s_row'], my_task['e_row'], my_task['category'],
                     my_task['req_id'], my_task['Master'], my_task['c_size'])
    elif (my_task['Approach'] == 'Horizontal' or my_task['Approach'] == 'Normal') and my_task['Algo'] == 'New':
        comp = new_mixed_H(my_task['sup'], my_task['conf'], my_task['s_row'],
                           my_task['e_row'], my_task['req_id'], my_task['Master'], my_task['c_size'])
    elif my_task['Approach'] == 'Vertical' and my_task['Algo'] == 'New':
        comp = new_mixed_V(my_task['sup'], my_task['conf'], my_task['s_row'], my_task['e_row'], my_task['category'],
                           my_task['req_id'], my_task['Master'])
    elif my_task['Approach'] == 'Hybrid' and my_task['Algo'] == 'New':
        comp = new_mixed_HY(my_task['sup'], my_task['conf'], my_task['s_row'], my_task['e_row'], my_task['category'],
                            my_task['req_id'], my_task['Master'], my_task['c_size'])
    else:
        pass

    if comp:
        ts = time.time()
        ts = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S.%f')
        db = pymysql.connect(host, user, pwd, db_name)
        cur = db.cursor()
        query = "update slaverequestdetails set status='{0}', slaverestimestamp='{1}' " \
                "where slaveReqID='{2}'".format("Complete", ts, my_task['req_id'])
        cur.execute(query)
        db.commit()
        cur.close()
        db.close()
        resp = jsonify({'ip': my_ip, 'message': "Completed"})
        return resp
    else:
        ts = time.time()
        ts = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S.%f')
        db = pymysql.connect(host, user, pwd, db_name)
        cur = db.cursor()
        query = "update slaverequestdetails set status='{0}', slaverestimestamp='{1}' " \
                "where slaveReqID='{2}'".format("Error", ts, my_task['req_id'])
        cur.execute(query)
        db.commit()
        cur.close()
        resp = jsonify({'ip': my_ip, 'message': "Not Completed"})
        resp.status_code = 400
        db.close()
        return resp


if __name__ == "__main__":
    app.run(port=5004)          #MAKE CHANGES
