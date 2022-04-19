$(document).ready(function () {
  let recentlyAddedManuscript = $('#recentlyAddedManuscript').DataTable({
    lengthChange: false,
    // searching: false,
    processing: true,
    // ordering: false,
    // serverSide: true,
    bInfo: false,
    ajax: {
      url: 'controllers/manuscriptController.php', // json datasource
      type: 'POST', // method  , by default get
      data: {
        getRecentAddedManuscript: true,
        recent: 1,
      },

      // success: function (row, data, index) {
      // console.log(row);
      // },

      error: function (data) {
        console.log(data);
        // error handling
      },
    },
    createdRow: function (row, data, index) {},
    columnDefs: [],
    fixedColumns: false,
    deferRender: true,
    scrollY: 200,
    // scrollX: false,
    // scroller: {
    //   loadingIndicator: true,
    // },
    stateSave: false,
  });
  //   countPendingApproval
  $.ajax({
    url: 'controllers/manuscriptController.php',
    type: 'POST',
    data: {
      pendingManuscript: 1,
    },
    success: function (data) {
      let resp = JSON.parse(data);
      $('#pendingManuscriptAdminDash').html(resp.recordsTotal);
    },
  });

  //   countPendingRequest
  $.ajax({
    url: 'controllers/manuscriptController.php',
    type: 'POST',
    data: {
      getRequestAdmin: 1,
    },
    success: function (data) {
      let resp = JSON.parse(data);
      $('#requestManusriptAdminDash').html(resp.recordsTotal);
    },
  });
});

// IP Address

$.ajax({
  url: 'controllers/newInformationController.php',
  type: 'POST',
  data: {
    ipChart: 1,
  },
  success: function (data) {
    $('#pageVisitor').html(data);
  },
});

//total users pie graph

var options = {
  series: getUsers(1),

  labels: getUsers(2),
  chart: {
    type: 'donut',
    width: '100%',
    height: '300px',
    fontFamily: 'Poppins',
  },
  title: {
    text: 'TOTAL: ' + getUsers(3),
    align: 'middle',
    margin: 10,
    offsetX: 0,
    offsetY: 143,
    floating: true,
    style: {
      fontSize: '15px',
      fontWeight: 'bold',
      fontFamily: 'Poppins',
      color: '#263238',
    },
  },
  legend: {
    show: true,
    position: 'top',
    horizontalAlign: 'center',
    fontSize: '12px',
    fontFamily: 'Poppins',
    fontWeight: 400,
    offsetX: -25,
    offsetY: 0,

    onItemClick: {
      toggleDataSeries: true,
    },
    onItemHover: {
      highlightDataSeries: true,
    },
  },
  noData: {
    text: 'Loading...',
  },
  responsive: [
    {
      breakpoint: 250,
      options: {
        chart: {
          width: 300,
        },
      },
    },
  ],
};

var chart = new ApexCharts(
  document.querySelector('#totalUsersPieGraph'),
  options
);
chart.render();

function getUsers(value) {
  let allUsers = null;
  let campus = null;
  let total = null;
  $.ajax({
    async: false,
    url: 'controllers/newInformationController.php',
    type: 'POST',
    data: {
      getAllUsers: 1,
    },
    success: function (data) {
      let resp = JSON.parse(data);
      allUsers = resp[0];
      campus = resp[1];
      total = resp[2];
    },
  });
  if (value == 1) {
    return allUsers;
  } else if (value == 2) {
    return campus;
  } else {
    return total;
  }
}

//Total Manuscripts Bar Graph
var options2 = {
  series: [
    {
      name: 'Total Manuscripts',
      data: getManuscripts(2),
    },
  ],

  xaxis: {
    categories: ['CABEIHM', 'CAS', 'CICS', 'CIT', 'COE', 'CTE'],
  },
  chart: {
    type: 'bar',
    width: '100%',
    height: '300px',
    fontFamily: 'Poppins',
  },

  plotOptions: {
    bar: {
      distributed: true,
    },
  },
  yaxis: {
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: false,
    },
    labels: {
      show: true,
      formatter: function (val) {
        return val;
      },
    },
  },
  legend: {
    show: false,
    position: 'bottom',
    horizontalAlign: 'center',
    fontSize: '12px',
    fontFamily: 'Poppins',
    fontWeight: 400,
    offsetX: -25,
    offsetY: 0,

    onItemClick: {
      toggleDataSeries: true,
    },
    onItemHover: {
      highlightDataSeries: true,
    },
  },

  noData: {
    text: 'Loading...',
  },
  responsive: [
    {
      breakpoint: 250,
      options: {
        chart: {
          width: 300,
        },
      },
    },
  ],
};

var chart2 = new ApexCharts(
  document.querySelector('#totalManuscriptsBarGraph'),
  options2
);
chart2.render();

function getManuscripts(value) {
  let dept = null;
  let count = null;
  $.ajax({
    async: false,
    url: 'controllers/newInformationController.php',
    type: 'POST',
    // dataType: 'json',
    data: {
      getAllManuscripts: 1,
    },
    success: function (data) {
      let resp = JSON.parse(data);
      dept = resp[0];
      count = resp[1];
    },
  });

  if (value == 1) {
    return dept;
  } else {
    return count;
  }
}
