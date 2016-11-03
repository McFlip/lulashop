import csv

with open('timezoneabrev.csv', 'rb') as tsvin:
     tsvin = csv.reader(tsvin, delimiter=',')
     for row in tsvin:
         print "<option value=\""+row[0]+"\">"+row[0]+" "+row[1]+"</option>"
