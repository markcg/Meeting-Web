/**
* First we will load all of this project's JavaScript dependencies which
* includes Vue and other libraries. It is a great starting point when
* building robust, powerful web applications using Vue and Laravel.
*/

require("./bootstrap");
const moment = require("moment");
window.meetingApp = (function() {
  const app = {};
  app.schedule = current => {
    if (current === null) {
      current = moment();
    }
    app.setHeader(current);
    app.generateSchedule();
  };

  app.setHeader = current => {
    if (current === null) {
      current = moment();
    }
    $("#first-header").text(moment(current).format("DD/MM/YYYY"));
    $("#second-header").text(
      moment(current).add(1, "day").format("DD/MM/YYYY")
    );
    $("#third-header").text(
      moment(current).add(2, "days").format("DD/MM/YYYY")
    );
    $("#fourth-header").text(
      moment(current).add(3, "days").format("DD/MM/YYYY")
    );
    $("#fifth-header").text(
      moment(current).add(4, "days").format("DD/MM/YYYY")
    );
    $("#sixth-header").text(
      moment(current).add(5, "days").format("DD/MM/YYYY")
    );
    $("#seventh-header").text(
      moment(current).add(6, "days").format("DD/MM/YYYY")
    );
  };

  app.generateSchedule = () => {
    const schedule = $("#schedule-list");
    schedule.empty();
    for (let i = 0; i < 17; i++) {
      const row = $("<tr></tr>");
      for (let k = 0; k <= 7; k++) {
        const data = $("<td></td>");
        if (k === 0) {
          data.text(i + 8);
        }
        row.append(data);
      }
      schedule.append(row);
    }
  };
  return app;
})();
