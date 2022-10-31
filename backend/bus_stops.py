import requests
import csv
import pandas as pd
import pprint
import sqlite3
import pymysql

# Reads in CSV data from API using pandas and outputs as a local CSV file
url = 'https://naptan.api.dft.gov.uk/v1/access-nodes?dataFormat=csv&atcoAreaCodes=049'

df = pd.read_csv(url)
df.head()
df.to_csv('out.csv')

bus_stops = []

# Reads through each line of the local CSV file and gets the required values
with open('out.csv') as output_file:
    output_file_dict = csv.DictReader(output_file)
    for row in output_file_dict:
        atco_code = row["ATCOCode"]
        naptan_code = row["NaptanCode"]
        stop_name = row["CommonName"]
        stop_street = row["Street"]
        stop_indicator = row["Indicator"]
        stop_direction = row["Bearing"]
        stop_town = row["LocalityName"]
        longitude = float(row["Longitude"])
        latitude = float(row["Latitude"])
        stop_type = row["StopType"]

        # Checks if the stop is for buses and appends values to a list
        if stop_type[0] == "B":
            bus_stops.append([atco_code,naptan_code,
                              stop_name,stop_street,stop_indicator,
                              stop_direction,stop_town,longitude,
                              latitude,stop_type])
        
# Connects to PHP MySQL database and creates a cursor
conn = pymysql.connect(host="localhost",
                       user="root",
                       port = 3306,
                       password="",
                       database="bus-timetabling")
cursor = conn.cursor()

# Prepares SQL statement and executes for each item in the list
sql = """INSERT INTO bus_stops (atco_code,
         naptan_code, name, street,
         indicator, bearing, town,
         longitude, latitude,
         stop_type) VALUES
         (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"""

for item in bus_stops:
    cursor.execute(sql,item)

conn.commit()
conn.close()