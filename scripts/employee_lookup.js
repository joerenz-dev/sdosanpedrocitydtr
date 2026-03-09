
				$(document).ready(function() {
					$('#employee_number').on('input', function() {
						var emp_id = $(this).val();

						if (emp_id.length > 0) {
							$.ajax({
								url: 'fetch_employee.php',
								type: 'POST',
								dataType: 'json',
								data: { employee_number: emp_id },
						
								success: function(response) {
									if (response.status === 'ok') {
										$('#employee_name').text(response.name);
										$('#time_logs').html(
											`AM-IN <strong>${response.time_in}</strong> | ` +
											`AM-OUT <strong>${response.lunch_out}</strong> | ` +
											`PM-IN <strong>${response.lunch_in}</strong> | ` +
											`PM-OUT <strong>${response.time_out}</strong>`
										);
									} else {
										$('#employee_name').text('Not found');
										$('#time_logs').html('');
									}
								}
							});
						} else {
							$('#employee_name').text('');
							$('#time_logs').text('');
						}
					});
				});