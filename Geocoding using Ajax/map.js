// Put your zillow.com API key here
var zwsid = "";

var request = new XMLHttpRequest();
finalvalue="";


function initialize() {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(32.7050, -97.1228);
    var mapOptions = {
      zoom: 17,
      center: latlng
    }
    map = new google.maps.Map(document.getElementById("map"), mapOptions);
	
	marker = new google.maps.Marker({
        position: new google.maps.LatLng(32.73984589753108, -97.11684465408325),
        map: map
      });
	  
	//Add listener
google.maps.event.addListener(map, "click", function (event) {
    var latitude = event.latLng.lat();
    var longitude = event.latLng.lng();
    console.log( latitude + ', ' + longitude );
	
	marker.setMap(null);
	marker = new google.maps.Marker({
        position: new google.maps.LatLng(latitude,longitude),
        map: map
      });
	  
	  var latlng = {lat: latitude, lng: longitude};
  geocoder.geocode({'location': latlng}, function(results, status) {
temp= results[0].formatted_address;
totaladdress=temp.split(",");
 address=totaladdress[0];
 city=totaladdress[1];
 statezip=totaladdress[2];
temp=statezip.split(" ");
 state=temp[1];
 zipcode=temp[2];
request.onreadystatechange = display1Result;
request.open("GET","proxy.php?zws-id="+zwsid+"&address="+address+"&citystatezip="+city+"+"+state+"+"+zipcode);
    request.withCredentials = "true";
    request.send(null);

  });
}); //end addListener
  }



function codeAddress() {
    
	var address = document.getElementById("address").value;
    var city = document.getElementById("city").value;
    var state = document.getElementById("state").value;
    var zipcode = document.getElementById("zipcode").value;
	
	
	var fulladdress=address+" "+city+" "+state+" "+zipcode;
	address=fulladdress;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
		marker.setMap(null);
        marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
			
        });
		
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }

  
  
function code1Address() {
    
	
	var fulladdress=address+" "+city+" "+state+" "+zipcode;
	address=fulladdress;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
		marker.setMap(null);
        marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
			
        });
		
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
	
  

function displayResult () {
    if (request.readyState == 4) {
        var xml = request.responseXML.documentElement;
        var value = xml.getElementsByTagName("zestimate")[0].getElementsByTagName("amount")[0].innerHTML;
		value="<br/><b>Cost: </b>$"+value
		codeAddress();
	finalvalue=finalvalue+"<br><br>"+"<b>Address: </b>"+address+value;
	document.getElementById("output").innerHTML = finalvalue;
    }
}

function display1Result () {
    if (request.readyState == 4) {
        var xml = request.responseXML.documentElement;
		
		console.log(xml.getElementsByTagName("message")[0].getElementsByTagName("code")[0].innerHTML);
		console.log(xml.getElementsByTagName("message")[0].getElementsByTagName("text")[0].innerHTML);
		
        
		
		
		if(xml.getElementsByTagName("message")[0].getElementsByTagName("code")[0].innerHTML=="0")
		{
			
			var value = xml.getElementsByTagName("zestimate")[0].getElementsByTagName("amount")[0].innerHTML;
			
			value="<br/><b>Cost: </b>$"+value
			code1Address();
		}
		
		if(xml.getElementsByTagName("message")[0].getElementsByTagName("code")[0].innerHTML=="508")
		{
		document.getElementById("output").innerHTML = "";
		var value = xml.getElementsByTagName("message")[0].getElementsByTagName("text")[0].innerHTML;
		value="<br/><b>Error: </b>"+value
			code1Address();
			
		}
		
		finalvalue=finalvalue+"<br><br>"+"<b>Address: </b>"+address+value;
	document.getElementById("output").innerHTML = finalvalue;
    }
}


function sendRequest () {
    request.onreadystatechange = displayResult;
    var address = document.getElementById("address").value;
    var city = document.getElementById("city").value;
    var state = document.getElementById("state").value;
    var zipcode = document.getElementById("zipcode").value;
    request.open("GET","proxy.php?zws-id="+zwsid+"&address="+address+"&citystatezip="+city+"+"+state+"+"+zipcode);
    request.withCredentials = "true";
    request.send(null);
	
	
	
}

function clearLog()
{
	document.getElementById("output").innerHTML = null;

}

function initMap(){
}