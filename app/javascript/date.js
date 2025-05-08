const hoursByDay = {
    0: ["13:00", "14:00", "15:00", "16:00", "17:00"], // ndz
    1: ["14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00"], // pon
    2: ["14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00"], // wt
    3: ["14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00"], // śr
    4: ["14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00"], // czw
    5: ["14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"], // pt
    6: ["12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00"], // sob
  };

  const dateInput = document.getElementById("date");
  const timeSelect = document.getElementById("time");

  dateInput.addEventListener("change", () => {
    const selectedDate = new Date(dateInput.value);
    const dayOfWeek = selectedDate.getDay();

    const availableHours = hoursByDay[dayOfWeek] || [];
    timeSelect.innerHTML = '<option value="">-- wybierz godzinę --</option>';

    availableHours.forEach(hour => {
      const option = document.createElement("option");
      option.value = hour;
      option.textContent = hour;
      timeSelect.appendChild(option);
    });
  });