import pandas as pd
import time
import pymysql
from sqlalchemy import create_engine
host = "localhost"
user = "root"
pwd = ""
db_name = "BE"
port = 3306
start = time.time()

db = pymysql.connect(host, user, pwd, db_name)


#Important
nrows = 1000000
table = input("Tablename:")
#skip = 0
skip = 0
url = "C:/Users/Abhishek/Desktop/BE_Extras/final.csv"




#Filling up Values
engine = create_engine("mysql+pymysql://{user}:{pw}@{host}/{db}".format(host=host, user=user, pw=pwd, db=db_name))
df = pd.read_csv(url, skiprows=range(1, skip+1), #nrows=nrows,
                 usecols=['order_id', 'product_id', 'department_id'], chunksize=2000000)
total = 0

for i in df:
    chunk = i.rename(columns={'order_id': 'TransID', 'product_id': 'ItemNo',
                              'department_id': 'Category_id'})
    chunk = chunk.dropna()
    #chunk['Seq'] += 1
    chunk['TransID'] += 30000000
    total += chunk.shape[0]
    chunk.to_sql(name=table, con=engine, if_exists='append', index=False)

print("Size:", total)
print(time.time() - start)

