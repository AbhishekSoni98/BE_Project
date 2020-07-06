$(document).ready(function(){

    var id = getUrlParameter('id');
    alert(id);
	$.ajax({
		url:"http://localhost/BE%20Project/MyDashboard/BarChartData.php?masterid="+id,
		method: "GET",
		success: function(data){
			console.log(data);
			var slaves = [];
			var cluster_count = [];

			for(var i in data){
				slaves.push("Slave" + data[i].SlaveID);
				cluster_count.push(data[i].cluster_count);
			}

			var chartdata = {
				labels: slaves,
				datasets : [
					{
						label : 'Slave Cluster',
						backgroundColor: 'rgba(0,120,200,1)',
						borderColor: 'rgba(0,120,200,1)',
						hoverBackgroundColor: 'rgba(0,120,200,1)',
						hoverBorderColor: 'rgba(0,120,200,1)',
						data: cluster_count
					}

				]
            };
            
            var options = {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            userCallback: function(label, index, labels) {
                                // when the floored value is the same as the value we have a whole number
                                if (Math.floor(label) === label) {
                                    return label;
                                }
            
                            },
                        }
                    }],
                },
            }

			var ctx = document.getElementById("myAreaChart");

			var BarGraph = new Chart(ctx,{
				type: 'bar',
                data: chartdata,
                options : options
			})
		},
		error: function(data){
			console.log(data);
		}
	});
});

