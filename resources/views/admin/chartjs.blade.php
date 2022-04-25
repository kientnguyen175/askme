<div style="display: flex; justify-content: space-between;">
   <div style="width: 19%">@php echo $userBox; @endphp</div>
   <div style="width: 19%">@php echo $questionBox; @endphp</div>
   <div style="width: 19%">@php echo $tagBox; @endphp</div>
   <div style="width: 19%">@php echo $answerBox; @endphp</div>
   <div style="width: 19%">@php echo $commentBox; @endphp</div>
</div>
<br><br>
<div class="chart-container" style="height:300px; width:50%; display:flex; margin-bottom: 50px">
    <canvas id="users-chart" style="margin-right: 10px"></canvas>
    <canvas id="tags-chart"></canvas>
</div>
<div class="chart-container" style="height:300px; width:50%; display:flex;">
    <canvas id="question-chart"></canvas>
    <canvas id="er-chart" style="margin-right: 10px"></canvas>
</div>
<script>
    $(function () {
        var ctx = document.getElementById("users-chart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($userLabels),
                datasets: [{
                    label: 'total users',
                    // data: @json($userData),
                    data: [
                        50,
                        60,
                        90,
                        120,
                        170,
                        200,
                        220
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },
                title: {
                    display: true,
                    text: 'Users'
                }
            }
        });
    });
</script>
<script>
    $(function () {
        var ctx = document.getElementById("tags-chart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($tagLabels),
                datasets: [{
                    label: 'My First Dataset',
                    // data: @json($tagData),
                    data: [
                        50,
                        90,
                        150,
                        300,
                        200,
                        400,
                        1000,
                        1200
                    ],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        '#22CFCF',
                        '#9966FF',
                        '#A1CECE',
                        '#8ddae8',
                        '#FF9F40',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Tags'
                }
            }
        });
    });
</script>
<script>
    $(function () {
        var ctx = document.getElementById("er-chart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($userLabels),
                datasets: [
                    {
                        label: 'engagement rate (%)',
                        fill: false,
                        // data: @json($erData),
                        data: [
                            50,
                            90,
                            120,
                            100,
                            90,
                            130,
                            150
                        ],
                        backgroundColor: [
                            '#DBF2F2'
                        ],
                        borderColor: [
                            '#4BC0C0',
                        ],
                        borderWidth: 2
                    },
                    {
                        label: 'normal efficiency (%)',
                        fill: false,
                        // data: @json($erData),
                        data: [
                            100,
                            100,
                            100,
                            100,
                            100,
                            100,
                            100,
                        ],
                        pointRadius: 0,
                        backgroundColor: [
                            '#FFE0E6'
                        ],
                        borderColor: [
                            '#FF6384',
                        ],
                        borderWidth: 2
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },
                title: {
                    display: true,
                    text: 'Engagement'
                },
            }
        });
    });
</script>
<script>
    $(function () {
        var ctx = document.getElementById("question-chart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($userLabels),
                datasets: [
                    {
                        label: 'total questions of the day',
                        // data: @json($tongTonDong),
                        data: [
                            50,
                            60,
                            70,
                            65,
                            80,
                            100,
                            75
                        ],
                        backgroundColor: [
                            '#9CD0F5',
                            '#9CD0F5',
                            '#9CD0F5',
                            '#9CD0F5',
                            '#9CD0F5',
                            '#9CD0F5',
                            '#9CD0F5',
                        ],
                        borderColor: [
                            '#36A2EC',
                            '#36A2EC',
                            '#36A2EC',
                            '#36A2EC',
                            '#36A2EC',
                            '#36A2EC',
                            '#36A2EC',
                        ],
                        borderWidth: 0
                    },
                    {
                        label: 'total resolved questions of the day',
                        // data: @json($tongCauHoiGiaiQuyetTrongNgay),
                        data: [
                            15,
                            15,
                            35,
                            10,
                            40,
                            30,
                            20
                        ],
                        backgroundColor: [
                            '#FEE7AC',
                            '#FEE7AC',
                            '#FEE7AC',
                            '#FEE7AC',
                            '#FEE7AC',
                            '#FEE7AC',
                            '#FEE7AC',
                        ],
                        borderColor: [
                            '#FFCD57',
                            '#FFCD57',
                            '#FFCD57',
                            '#FFCD57',
                            '#FFCD57',
                            '#FFCD57',
                            '#FFCD57',
                        ],
                        borderWidth: 0
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },
                title: {
                    display: true,
                    text: 'Questions'
                }
            }
        });
    });
</script>
