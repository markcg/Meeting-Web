/**
* First we will load all of this project's JavaScript dependencies which
* includes Vue and other libraries. It is a great starting point when
* building robust, powerful web applications using Vue and Laravel.
*/

require("./bootstrap");
const _ = require("lodash");
const moment = require("moment");
window.meetingApp = (function() {
  const app = {
    schedules: [],
    current: null
  };
  app.schedule = current => {
    console.log(current);
    if (current) {
      current = moment(current);
    } else {
      current = moment();
    }
    app.current = current;
    app.setHeader(current);
    app.generateSchedule(current);
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
  app.createForm = (date, time, type, obj) => {
    const form = $('<form method="post" action=""></form>');
    const dateInput = $('<input type="hidden" name="date" />');
    dateInput.val(date);
    const timeInput = $('<input type="hidden" name="time" />');
    timeInput.val(time);
    const typeInput = $('<input type="hidden" name="type" value="reserved" />');
    const button = $('<button type="submit"></button>');
    if (type === "reserved") {
      button.text("Reserved");
      button.addClass("btn btn-warning");
      button.click(() => {
        return confirm("Remove this reserved?");
      });
    } else {
      button.text("Available");
      button.addClass("btn btn-success");
      button.click(() => {
        return confirm("Confrim reservation?");
      });
    }

    form.append(dateInput);
    form.append(timeInput);
    form.append(typeInput);
    form.append(button);
    if(obj){
      const id = $('<input type="hidden" name="id" />');
      id.val(obj.id);
      form.append(id);
    }
    return form;
  };
  app.generateSchedule = current => {
    const schedule = $("#schedule-list");
    schedule.empty();
    for (let i = 0; i < 17; i++) {
      const row = $("<tr></tr>");
      for (let k = 0; k <= 7; k++) {
        const data = $("<td></td>");
        const haveSchedule = _.find(app.schedules[k - 1], { time: i + 8 });
        // console.log(app.schedules[k]);
        let button = null;
        const date = moment(current).add(k - 1 , "days").format("YYYY-MM-DD")
        // console.log(haveSchedule);
        if (haveSchedule !== undefined) {
          button = app.createForm(date, i + 8, 'reserved', haveSchedule);
        } else {
          button = app.createForm(date, i + 8);
        }

        // button.onClick();
        data.append(button);
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
