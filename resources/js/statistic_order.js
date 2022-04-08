$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  
    // loadRevenue();
    if($('#btn-date-filter-order')){
        loadOrder();
    }

    function loadCanvas(total_order){
        $('#line-chart').remove();
        $('#graph-container').append('<canvas id="line-chart"><canvas>');
            var barChart = $('#line-chart');
                var myChart = new Chart(barChart, {
                    type: 'bar',
                    data: {
                        labels: ['Week 1','Week 2','Week 3','Week 4'],
                        datasets: [{
                                label: 'Total orders',
                                data: total_order.split(','),
                                backgroundColor: 'rgba(0, 128, 128, 0.7)',
                                borderColor: 'rgba(0, 128, 128, 0.7)',
                                borderWidth: 1
                            },
                        ]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        },
                    }
                });
    }

    function loadOrder(){
        var month = $('#select_month').val();
        var year = $('#select_year').val();
        var url = $('#btn-date-filter-order').data('url');
        $.ajax({
            url: url,
            method: 'post',
            data: {
                month: month,
                year: year,
            },
            success: function(data) {
                loadCanvas(data);
            },
            error: function (error) {
               toastr.error(error);
            },
        });
    }

    $('#btn-date-filter-order').click(function() {
        var month = $('#select_month').val();
        var year = $('#select_year').val();
        var url = $(this).data('url');
        $.ajax({
            url: url,
            method: 'post',
            data: {
                month: month,
                year: year,
            },
            success: function(data) {
                loadCanvas(data);
            },
            error: function (error) {
                toastr.error(error);
            },
        });
    });
});
