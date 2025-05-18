from flask import Flask, request, jsonify # type: ignore
import xml.etree.ElementTree as ET

app = Flask(__name__)
XML_FILE = 'members.xml'

@app.route('/get-members', methods=['GET'])
def get_members():
    with open(XML_FILE, 'r') as file:
        return file.read(), 200, {'Content-Type': 'application/xml'}

@app.route('/add-member', methods=['POST'])
def add_member():
    new_member = request.json
    tree = ET.parse(XML_FILE)
    root = tree.getroot()

    member = ET.SubElement(root, 'member')
    ET.SubElement(member, 'name').text = new_member['name']
    ET.SubElement(member, 'plan').text = new_member['plan']
    ET.SubElement(member, 'status').text = new_member['status']
    ET.SubElement(member, 'joinDate').text = new_member['joinDate']
    ET.SubElement(member, 'membershipDuration').text = new_member['membershipDuration']

    tree.write(XML_FILE)
    return jsonify({'message': 'Member added successfully!'}), 201

if __name__ == '__main__':
    app.run(debug=True)
