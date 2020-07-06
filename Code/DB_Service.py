import pandas as pd
import time
from sqlalchemy import create_engine
host = "localhost"
user = "root"
pwd = ""
db_name = "BE"


def to_staging(path):
    start = time.time()
    engine = create_engine("mysql+pymysql://{user}:{pw}@{host}/{db}".format(host=host, user=user, pw=pwd, db=db_name))
    df = pd.read_csv(path,
                     usecols=['Unnamed: 0', 'order_id', 'product_id', 'department_id'], chunksize=2000000)
    total_rows = 0
    for i in df:
        chunk = i.rename(columns={'Unnamed: 0': 'Seq', 'order_id': 'TransID', 'product_id': 'ItemNo',
                                  'department_id': 'Category_id'})
        chunk = chunk.fillna(0)
        chunk['Seq'] += 1
        total_rows += chunk.shape[0]
        chunk.to_sql(name='staging_incremental', con=engine, if_exists='replace', index=False)

    print("Size:", total_rows)
    print(time.time() - start)
    return total_rows
