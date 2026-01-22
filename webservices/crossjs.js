



function get_localhost_data()
{
	$.ajax({
	            url: "http://localhost/planetgym/webservices/sample.php",
	            //url: "sample.php",
	            type: "POST",
	            crossDomain: true,
	            //data: JSON.stringify(somejson),
	            //dataType: "json",
	            headers: {
                    'Access-Control-Allow-Origin': 'http://192.168.0.81'
                },
	            success: function (response) {
	                //var resp = JSON.parse(response)
	                alert(response);
	            },
	            error: function (xhr, status) {
	                alert("error");
	            }
	        });
}