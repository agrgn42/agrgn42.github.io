from flask import Flask, render_template, request, flash, jsonify
from forms import ContactForm
from secrets import MAPBOX_ACCESS_KEY
import json
 
app = Flask(__name__) 
 
app.secret_key = 'whiskii^honk#)ywooptonksh&'


@app.route('/contact', methods=['GET', 'POST'])
def contact():
  form = ContactForm()
 
  if request.method == 'POST':
    return 'Form posted.'
 
  elif request.method == 'GET':
    return render_template('contact.html', form=form)



@app.route("/", methods=['GET', 'POST'])
def index():
	## return the geo data and mapbox key to the map page
	fname = 'dance.geojson'
	fopen = open(fname, 'r')
	DANCE_GEOJSON = fopen.read()
	fopen.close()

	# DANCE_GEOJSON = jsonify(fstring)
	return render_template("index.html", ACCESS_KEY=MAPBOX_ACCESS_KEY, GEOJSON=DANCE_GEOJSON)



@app.route("/dance.geojson", methods=['GET'])
def geojson():
	## return the geo data and mapbox key to the map page
	# DANCE_GEOJSON = jsonify(fstring)
	return render_template("index.html", ACCESS_KEY=MAPBOX_ACCESS_KEY)





if __name__=="__main__":
    # model.init()
    app.run(debug=True)