Chart.defaults.global.defaultFontColor = '#858796';
$(document).ready(function(){
	$.ajax({
		url:"http://localhost/BE%20Project/MyDashboard/Piedata.php",
		method: "GET",
		success: function(data){
			console.log(data);
			var status = [];
			var status_count = [];
			

			for(var i in data){
				status.push("Status" + data[i].Status);
				status_count.push(data[i].status_count);
			
			}

			var chartdata = {
				labels: status,
				datasets : [
					{
						labels: ["Not Assigned", "Complete", "In Progress"],
						backgroundColor: ['#5bc0de', '#5cb85c', '#0275d8'],
						borderColor: ['#5bc0de', '#5cb85c', '#0275d8'],
						hoverBackgroundColor: ['#5bc0de', '#5cb85c', '#0275d8'],
						hoverBorderColor: 'rgba(234, 236, 244, 1)',
						data: status_count
					}

				]

			};

			var ctx = document.getElementById("myPieChart");

			var myPieChart = new Chart(ctx,{
				type: 'doughnut',
				data: chartdata,
				options: {
						    maintainAspectRatio: false,
						    tooltips: {
						      backgroundColor: "rgb(255,255,255)",
						      bodyFontColor: "#858796",
						      borderColor: '#dddfeb',
						      borderWidth: 1,
						      xPadding: 15,
						      yPadding: 15,
						      displayColors: false,
						      caretPadding: 10,
						    },
						    legend: {
						      display: false
						    },
						    cutoutPercentage: 80,
						  },
			})
			
		},
		error: function(data){
			console.log(data);
		}
	});
});