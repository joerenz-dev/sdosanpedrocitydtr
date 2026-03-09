	// FETCH AND DISPLAY ACTION BUTTONS BASED ON EMPLOYEE NUMBER
		document.getElementById("employee_number").addEventListener("input", function () {
		const empNum = this.value.trim();

		const actionDiv = document.getElementById("actionButtons");
			if (!actionDiv) return;

			if (empNum.length === 0) {
				actionDiv.innerHTML = '';
				return;
			}

		fetch("check_timein_status.php?employee_number=" + encodeURIComponent(empNum))
			.then(res => res.json())
			.then(data => {
				console.log("Fetched button data:", data); // Debug line

				actionDiv.innerHTML = "";
				actionDiv.style.display = "flex";
				actionDiv.style.justifyContent = "center";
				actionDiv.style.flexWrap = "wrap";
				actionDiv.style.gap = "1px";

				const btnStyle = "font-size:1vw; padding:5px;width:75px;";

				const labelMap = {
					"time_in": "AM - in",
					"lunch_out": "AM - out",
					"lunch_in": "PM - in",
					"time_out": "PM - out"
				};

				const buttonOrder = ["time_in", "lunch_out", "lunch_in", "time_out"];
				const buttonMap = new Map((data.buttons || []).map(b => [b.action, b]));

				buttonOrder.forEach(action => {
					const buttonData = buttonMap.get(action);
					if (!buttonData) return;

					const button = document.createElement("button");
					button.type = "submit";
					button.name = "action";
					button.value = action;
					button.innerText = labelMap[action] || action;
					button.className = "buttons";
					button.style = btnStyle;

					if (!buttonData.enabled) {
						button.disabled = true;
						button.style.opacity = 0.5;
						button.style.cursor = "not-allowed";
					}

					actionDiv.appendChild(button); // Append directly
				});
			})
			.catch(err => {
				console.error("Error fetching button status:", err);
				actionDiv.innerHTML = "<p style='color:red;'>Error loading actions.</p>";
			});
		});