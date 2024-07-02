import folium
import pymysql
import sys
import decimal
from decimal import Decimal

conn = pymysql.connect(
    host='localhost',
    user='root',
    password='',
    db='toll_system',
)

# Define the coordinates of the six places
places = {
    'New York': (40.7128, -74.0060),
    'Atlantic City': (39.3643, -74.4229),
    'New Jersey': (40.0583, -74.4057),
    'Pennsylvania': (41.2033, -77.1945),
    'Philadelphia': (39.9526, -75.1652),
    'Washington DC': (38.9072, -77.0369)
}

# Create a map centered around the first place
m = folium.Map(location=places['New York'], zoom_start=10)

# Mark the six places on the map
for place, coord in places.items():
    folium.Marker(location=coord, popup=place).add_to(m)

# Define the roads (both toll and non-toll)
roads = [
    ('New York', 'Atlantic City'),
    ('New York', 'New Jersey'),
    ('New York', 'Pennsylvania'),
    ('New York', 'Philadelphia'),
    ('Atlantic City', 'New Jersey'),
    ('Atlantic City', 'Pennsylvania'),
    ('Atlantic City', 'Philadelphia'),
    ('Atlantic City', 'Washington DC'),
    ('New Jersey', 'Pennsylvania'),
    ('New Jersey', 'Philadelphia'),
    ('New Jersey', 'Washington DC'),
    ('Pennsylvania', 'Philadelphia'),
    ('Pennsylvania', 'Washington DC'),
    ('Philadelphia', 'Washington DC'),
    ('Washington DC', 'New York'),
]

# Add toll and non-toll roads
for start, end in roads:
    folium.PolyLine([places[start], places[end]], color='pink', weight=5).add_to(m)
    folium.PolyLine([places[start], places[end]], color='blue', weight=3, dash_array='5, 10').add_to(m)

# Save the map to an HTML file
m.save('map.html')

import math

def haversine(coord1, coord2):
    # Calculate the great-circle distance between two points on the Earth
    lat1, lon1 = coord1
    lat2, lon2 = coord2
    R = 6371  # Earth radius in kilometers

    phi1, phi2 = math.radians(lat1), math.radians(lat2)
    dphi = math.radians(lat2 - lat1)
    dlambda = math.radians(lon2 - lon1)

    a = math.sin(dphi / 2) ** 2 + math.cos(phi1) * math.cos(phi2) * math.sin(dlambda / 2) ** 2
    distance = 2 * R * math.atan2(math.sqrt(a), math.sqrt(1 - a))
    return distance

def calculate_toll(start, end, price_per_km=0.1):
    distance = haversine(places[start], places[end])
    return distance * price_per_km

def check_user_registration(user_id):
    with conn.cursor() as cursor:
        # SQL query to check if the user ID exists in the table
        sql_query = f"SELECT COUNT(1) FROM users WHERE id = %s"
        cursor.execute(sql_query, (user_id,))
        result = cursor.fetchone()

        if result[0] > 0:
            print("User is registered.")
            # Proceed with the rest of the code
        else:
            print("User is not registered.")
            sys.exit()
        
id=input('Enter your user id ')
check_user_registration(id)

print('Type source and destination from "New York", "Atlantic City", "New Jersey", "Pennsylvania", "Philadelphia" and "Washington DC"')
start_place = input('Enter source ')
end_place = input('Enter destination ')
choice = input('Select "toll"/"non-toll" road ')

if choice == 'toll':
    toll_amount = calculate_toll(start_place, end_place)
    print(f"Toll amount from {start_place} to {end_place}: ${toll_amount:.2f}")
    with conn.cursor() as cursor:
        # Create a new record
        sql = "INSERT INTO transactions (user_id, amount, transaction_type) VALUES (%s, %s, %s)"
        cursor.execute(sql, (id, toll_amount, "toll"))

    # Commit changes
    conn.commit()
else:
    toll_amount = 0.00
    print(f"Toll amount from {start_place} to {end_place}: ${toll_amount:.2f}")

#print(f"Toll amount from {start_place} to {end_place}: ${toll_amount:.2f}")


#with conn.cursor() as cursor:
    # Create a new record
    #sql = "INSERT INTO transactions (user_id, amount) VALUES (%s, %s)"
    #cursor.execute(sql, (id, toll_amount))

# Commit changes
#conn.commit()

#print("Record inserted successfully")


with conn.cursor() as cursor:
    # Update a record
    query = "SELECT balance FROM users WHERE id=%s"
    cursor.execute(query, (id))
    result = cursor.fetchone()
    if result:
        value = result[0]
        print("Previous balance: $", value)
    else:
        print("No result found")
    toll_amount = Decimal(toll_amount)
    toll_amount=toll_amount.quantize(decimal.Decimal('0.00'))
    newvalue = value - toll_amount
    sql = "UPDATE users SET balance=%s WHERE id=%s"
    cursor.execute(sql, (newvalue, id))
    print("New balance: $", newvalue)

# Commit changes
conn.commit()


