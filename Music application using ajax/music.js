// Put your Last.fm API key here
var api_key = "";

function requestArtistInfo()
{
	var xhr = new XMLHttpRequest();
    var method = "artist.getInfo";
	
	
    var artist = encodeURI(document.getElementById("form-input").value);
    xhr.open("GET", "proxy.php?method="+method+"&artist="+artist+"&api_key="+api_key+"&format=json", true);
	xhr.setRequestHeader("Accept","application/json");
    xhr.onreadystatechange = function () {
        if (this.readyState == 4) {
			var json = JSON.parse(this.responseText);
            str=" Linked with "
			for (i = 0; i < 5; i++) { //retrieve Tags
			str+="<p>"+(i+1)+": "+json.artist.tags.tag[i].name+" ";
									} 
									
            document.getElementById("name").innerHTML="<p>Artist:"+json.artist.name+"</p>";
			document.getElementById("link").innerHTML="<a href='"+json.artist.bio.links.link.href+"'>Webpage</a>";
			
			document.getElementById("biography").innerHTML="<q>Artist Biography<:"+json.artist.bio.content+"</q>";
			
			document.getElementById("image").innerHTML="<img src='"+json.artist.image[3]['#text']+"'/>";
			document.getElementById("tags").innerHTML="<p>"+str+"</p>";
			
			
			
			
			
        }
    };
	
	
	
	 xhr.send(null);
	
}



function requestTopAlbums()
{
	var xhr = new XMLHttpRequest();
    var method = "artist.getTopAlbums";
	
	
    
	
    var artist = encodeURI(document.getElementById("form-input").value);
    xhr.open("GET", "proxy.php?method="+method+"&artist="+artist+"&api_key="+api_key+"&format=json", true);
	xhr.setRequestHeader("Accept","application/json");
    xhr.onreadystatechange = function () {
        if (this.readyState == 4) {
            var json = JSON.parse(this.responseText);
            
			var text="Album ";
            str="" ;
			
			for (i = 0; i < 10; i++) { //top 10 albums
			str+="<p>Album"+(i+1)+":"+json.topalbums.album[i].name+"</p><img src='"+json.topalbums.album[i].image[2]['#text']+"'/>";
}

		document.getElementById("topAlbum").innerHTML = str;
		
			
        }
    };
	
	
	
	 xhr.send(null);
}

function requestSimilarArtists()
{
	var xhr = new XMLHttpRequest();
    var method = "artist.getSimilar";
	
	
    
	
    var artist = encodeURI(document.getElementById("form-input").value);
    xhr.open("GET", "proxy.php?method="+method+"&artist="+artist+"&api_key="+api_key+"&format=json", true);
	xhr.setRequestHeader("Accept","application/json");
    xhr.onreadystatechange = function () {
        if (this.readyState == 4) {
            var json = JSON.parse(this.responseText);
    
			var str="List of Similar Artists\n ";
           
			
			for (i = 0; i < 7; i++) { //retrieve similar artists
			str+="<p>"+(i+1)+": "+json.similarartists.artist[i].name+"</p><img src='"+json.similarartists.artist[i].image[2]['#text']+"'/>";
									}

		document.getElementById("similarArtist").innerHTML = str;
			
			
        }
    };
	
	
	
	 xhr.send(null);
}
function sendRequest () {
    
	requestArtistInfo();
	requestTopAlbums();
	requestSimilarArtists();
  
}
