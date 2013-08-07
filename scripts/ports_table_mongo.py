#!/usr/bin/python
import MySQLdb
import pymongo
from pymongo import MongoClient


#connect mongo
client = MongoClient()
mongodb = client.ratemgt

# remove any old ports
mongodb.ports.remove()


db = MySQLdb.connect(host="localhost", # your host, usually localhost
                     user="rahul", # your username
                      passwd="boar", # your password
                      db="rate_management",
                      charset='utf8') # name of the data base

# you must create a Cursor object. It will let
#  you execute all the query you need
cur = db.cursor() 

# Use all the SQL you like
cur.execute("SELECT id, name, country_code, port_code, rail, road, airport, ocean, multimodal, border_crossing, latitude, longitude, found, map_geocode, search_term, state_code, hit_count, wp_url FROM ref_ports")

# print all the first cell of all the rows
for row in cur.fetchall() :
    print "Working on {0}, ID: {1}".format(row[1].encode('utf-8').strip(), row[0])

    port_id = row[0]
    name = row[1].encode('utf-8').strip()
    country_code = row[2]
    port_code = row[3]
    rail = row[4]
    road = row[5]
    airport = row[6]
    ocean = row[7]
    multimodal = row[8]
    border_crossing = row[9]
    latitude = row[10]
    longitude = row[11]
    found = row[12]
    search_term = row[14].encode('utf-8').strip()
    state = row[15]
    hits = row[16]
    wp_url = row[17]
    port = {"_id": port_id,
            "name": name,
            "country_code": country_code,
            "port_code" : port_code,
            "search_term" : search_term,
            }
    if longitude != None and latitude != None:
        port['location'] = {"type" : "Point" , "coordinates" : [longitude, latitude]}        
            
    
    mongodb.ports.insert(port)