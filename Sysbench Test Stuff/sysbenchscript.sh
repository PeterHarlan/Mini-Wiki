#!/bin/bash

for i in 128MB 256MB 512MB 1GB 2GB 4GB 8GB
do
  sysbench --test=fileio --file-total-size=$i prepare
  sysbench --test=fileio --file-total-size=$i --file-test-mode=rndrw --init-rng=on --max-time=300 --max-requests=0 run > test$i.txt
  sysbench --test=fileio --file-total-size=$i --file-test-mode=rndrw --init-rng=on --max-time=300 --max-requests=0 run >> test$i.txt
  sysbench --test=fileio --file-total-size=$i --file-test-mode=rndrw --init-rng=on --max-time=300 --max-requests=0 run >> test$i.txt
  sysbench --test=fileio --file-total-size=$i cleanup
done

for j in 1 2 4 8 16 32
do
  sysbench --num-threads=$j --test=cpu --cpu-max-prime=15000 run > cputest$j.txt
  sysbench --num-threads=$j --test=cpu --cpu-max-prime=15000 run >> cputest$j.txt
  sysbench --num-threads=$j --test=cpu --cpu-max-prime=15000 run >> cputest$j.txt
done

for x in 1 2 4 8 16 32
do
  sysbench --test=oltp --oltp-table-size=1000000 --mysql-db=test --mysql-user=root --mysql-password=wku2000 --db-driver=mysql prepare
  sysbench --test=oltp --oltp-table-size=1000000 --mysql-db=test --mysql-user=root --mysql-password=yourrootsqlpassword --max-time=60 --oltp-read-only=on --max-requests=0 --num-threads=$x run > sqltest$x.txt
  sysbench --test=oltp --oltp-table-size=1000000 --mysql-db=test --mysql-user=root --mysql-password=yourrootsqlpassword --max-time=60 --oltp-read-only=on --max-requests=0 --num-threads=$x run >> sqltest$x.txt
  sysbench --test=oltp --oltp-table-size=1000000 --mysql-db=test --mysql-user=root --mysql-password=yourrootsqlpassword --max-time=60 --oltp-read-only=on --max-requests=0 --num-threads=$x run >> sqltest$x.txt
  sysbench --test=oltp --mysql-db=test --mysql-user=root --mysql-password=wku2000 --db-driver=mysql cleanup
done