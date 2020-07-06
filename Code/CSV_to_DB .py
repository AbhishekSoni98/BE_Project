import math
import time
import pymysql
import pandas as pd

#variables
host = "localhost"
user = "root"
pwd = ""
db_name = "BE"
url = "C:/Users/Abhishek/Desktop/BE_Project/final.csv"
nrows = 5000000
skip = 0

#Connections
db = pymysql.connect(host, user, pwd, db_name)
df = pd.read_csv(url, nrows=nrows, skiprows=skip)
cur = db.cursor()

start = time.time()
for i in range(nrows):
    row = df.iloc[i]
    t_id = int(row['order_id'])
    p_id = int(row['product_id'] if not math.isnan(row['product_id']) else 0)
    d_id = int(row['department_id'] if not math.isnan(row['product_id']) else 0)
    cur.execute('insert into DmarttransDetails2(`TransID`, `ItemNo`, `Category_id`) values("{0}","{1}","{2}")'.format( t_id, p_id, d_id))
    db.commit()
cur.close()
db.close()
print(time.time()-start)

