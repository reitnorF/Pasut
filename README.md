# Pasut

## Proses Seluruh Data (date=all)
http://localhost/pasut/?station=0045WGPO02&date=all


mysql -uroot -proot --local_infile=1 pasut -e "LOAD DATA LOCAL INFILE 'data_vsat5.csv' INTO TABLE data_vsat5 FIELDS TERMINATED BY '\t'" 
