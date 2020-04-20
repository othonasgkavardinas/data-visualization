#import MySQLdb
import pymysql

db = pymysql.connect("localhost", "root", "", "mydb", local_infile=1)
cursor = db.cursor()
cursor.execute("SELECT VERSION()")
data = cursor.fetchone()
print("Database version : %s " % data)

query = r"""LOAD DATA LOCAL INFILE 'countries.csv'
    INTO TABLE mydb.Country
    FIELDS TERMINATED BY ','
    ENCLOSED BY '"'
    LINES TERMINATED BY '\n';"""
cursor.execute(query)
db.commit()

query = r"""LOAD DATA LOCAL INFILE 'years.csv'
    INTO TABLE mydb.Year
    FIELDS TERMINATED BY ','
    ENCLOSED BY '"'
    LINES TERMINATED BY '\n';"""
cursor.execute(query)
db.commit()

query = r"""LOAD DATA LOCAL INFILE 'measures.csv'
    INTO TABLE mydb.Measure
    FIELDS TERMINATED BY ','
    ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    (Years_Y_ID, Countries_C_ID, M_Category, M_Value, M_Type);"""
cursor.execute(query)
db.commit()

db.close()
