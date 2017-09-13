"""`main` is the top level module for your Flask application."""

# Import the Flask Framework
import pymongo
import gridfs
from flask import Flask,render_template,request,session
from pymongo import MongoClient
from gridfs import GridFS
from gridfs.errors import NoFile
import uuid
import base64
import datetime
from werkzeug import secure_filename



client = MongoClient('mongodb://146.148.100.136/')

db = MongoClient('mongodb://146.148.100.136:27017/').gridfs_example
fs = gridfs.GridFS(db)
#fs = gridfs.GridFS(client)

ALLOWED_EXTENSIONS = set(['PNG', 'jpg', 'jpeg', 'gif'])


app = Flask(__name__)
app.secret_key="tarinikey"



# Note: We don't need to call run() since our application is embedded within
# the App Engine WSGI application server.




@app.route('/')
def index():
    if session.get('logged_in')==True:
        return render_template('upload.html')
    else:
        return render_template('login.html')

		
@app.route('/login',methods=['GET','POST'])
def login():
    u=request.form['user'];
    p=request.form['pass'];
    users=client.mydb.login.find_one({"username":u})
    pass1=users['password']

    if str(pass1)==str(p):
        session['logged_in']=True
        return render_template('upload.html')
    else:
        session['logged_in']=False
        return render_template('login.html')

def allowed_file(filename):
    return '.' in filename and \
            filename.rsplit('.', 1)[1] in ALLOWED_EXTENSIONS



def insert_img(username,post_id,image_data,filename,cmnt):
    img_coll = client.mydb.images
    post_dict = {}
    post_dict['filename']=filename
    post_dict['username']=username
    post_dict['post_id']=post_id
    encoded_string = base64.b64encode(image_data)
    post_dict['image_data']=encoded_string
    post_dict['post_time']=str(datetime.datetime.now())
    post_dict['comments']=str(cmnt)
    output = img_coll.save(post_dict)
    return str(output)+""+str(cmnt)

@app.route('/upload',methods =['GET','POST'])
def upload():
	print 'in upload'
	file = request.files['myfile']
	cmnt = request.form['comments']
	username = "user1"
	print "test"
	if file and allowed_file(file.filename):
		fname = "C:/Users/Chanakya/Desktop/Cloud/gooogle/"+str(file.filename)
		image_data = open(fname, "rb").read()
		post_id = str(uuid.uuid1())
		print "test "
		output=insert_img(username,post_id,image_data,str(file.filename),cmnt)

	return str(output)

@app.route('/show',methods =['GET','POST'])
def show():
    l=""
    images1=client.tarini.images.find({"username":"user1"})
    for images2 in images1:
        imagename=images2['filename']
        print "imagesname: "+str(imagename)
        l=l+"<a href='"+imagename+"'/>"+imagename+"</a><br>"

    return l
    

    
            
    
    

@app.route('/logout')
def logout():
    session['logged_in']=False
    return render_template('login.html')
    


@app.errorhandler(404)
def page_not_found(e):
    """Return a custom 404 error."""
    return 'Sorry, Nothing at this URL.', 404


@app.errorhandler(500)
def application_error(e):
    """Return a custom 500 error."""
    return 'Sorry, unexpected error: {}'.format(e), 500

if __name__ == "__main__":
    
    app.run()


