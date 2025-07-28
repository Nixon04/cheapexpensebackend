#!/bin/bash

for i in {1..1000}; do
  # Generate random amount between 100 and 150
  amount=$((RANDOM % 51 + 100))
  
  # Generate a unique reference for each request
  ref1=$(uuidgen | tr -d '-')
  ref2=$(uuidgen | tr -d '-')
  ref3=$(uuidgen | tr -d '-')

  # User 1
  curl -s -X POST http://192.168.81.149:9000/api/purchaseairtime \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d "{
      \"username\": \"Nixonsampson\",
      \"amount\": \"$amount\",
      \"type_of_purchase\": \"Airtime\",
      \"sub_type_purchase\": \"MTN\",
      \"data_type\": \"VTU\",
      \"userpin\": \"1234\",
      \"status\": \"success\",
      \"ref_num_purchase\": \"09068225050\",
      \"date_of_purchase\": \"July 27, 2025\",
      \"reference\": \"$ref1\"
    }" &

  # User 2
  curl -s -X POST http://192.168.81.149:9000/api/purchaseairtime \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d "{
      \"username\": \"gpxx\",
      \"amount\": \"$amount\",
      \"type_of_purchase\": \"Airtime\",
      \"sub_type_purchase\": \"MTN\",
      \"data_type\": \"VTU\",
      \"userpin\": \"1234\",
      \"status\": \"success\",
      \"ref_num_purchase\": \"09068225050\",
      \"date_of_purchase\": \"July 27, 2025\",
      \"reference\": \"$ref2\"
    }" &

  # User 3
  curl -s -X POST http://192.168.81.149:9000/api/purchaseairtime \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d "{
      \"username\": \"gxxxx\",
      \"amount\": \"$amount\",
      \"type_of_purchase\": \"Airtime\",
      \"sub_type_purchase\": \"MTN\",
      \"data_type\": \"VTU\",
      \"userpin\": \"1234\",
      \"status\": \"success\",
      \"ref_num_purchase\": \"09068225050\",
      \"date_of_purchase\": \"July 27, 2025\",
      \"reference\": \"$ref3\"
    }" &

done

wait

