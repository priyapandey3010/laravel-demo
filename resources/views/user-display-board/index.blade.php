<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NCLAT Display Board</title>
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        .border-header-bottom {
        height: 31px;
        }a.nav-link {
    color: #fff;
}
    </style>
  </head>
  <body>
      
    <div class="border-header-top"></div>
        <section class="header">
          <div class="container">
              <div class="row">
                <div class="col-lg-10 col-md-10 com-sm-12">
                    <div class="brand">
                        <img src="{{ asset('front/img/logonclt.png') }}">
                        <h2>National Company Law Tribunal at New Delhi</h2>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 text-end">
                    <div class="calendar">
                        <p>{{ date('d-m-Y') }} <br> <span>{{ date('l') }}</span></p>
                    </div>
                </div>
              </div>
          </div>
        </section>
   <div class="border-header-bottom">
        <nav class="navbar navbar-expand-sm">

  <!-- Links -->
          <ul class="navbar-nav" style="margin-top: 10px;padding-right: 24px;right: 0;position: fixed;">
            <li class="nav-item">
              <a class="nav-link" href="{{ url('cases') }}">CASE STATUS</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url('judge') }}">  ORDERS/JUDGEMENT</a>
            </li>
          </ul>

        </nav>
    </div>

    <div class="container">
    <section class="filter-sec">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
            {{ Form::select('bench_id', bench_list(), session('bench_id') ?? null, ['class' => 'form-control', 'id' => 'bench_id']) }}
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 text-end">
                @isset($statuses)
                <div class="color-locator">
                    @foreach ($statuses as $status)
                    <div class="items">
                        <span style="background-color: {{ $status->colour_code }}"></span>
                        <p>{{ $status->status }}</p>
                    </div>
                    @endforeach
                </div>
                @endisset
            </div>
        </div>
    </section>
    </div>

    <section class="content-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2 class="content-heading">In Session</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">SN.</th>
                                <th>Court No.</th>
                                <th>Item</th>
                                <th>Case</th>
                                <th>Cause Title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        @isset($cases)
                        <tbody id="tbody">
                            @foreach ($cases as $idx => $case)
                            <tr style="background-color: {{ $case['colour_code'] }}">
                                <td class="text-white text-center" style="border-left-style: solid; border-top-left-radius: 10px;">{{ $idx+1 }}</td>
                                <td class="text-white">{{ $case['court_number'] }} ({{ $case['bench_name'] }})</td>
                                @if ($case['status'] === 'In Session')
                                <td class="text-white">{{ $case['item_number'] }}</td>
                                <td class="text-white">{{ $case['case_number'] }}</td>
                                <td class="text-white">{{ $case['case_title'] }}</td>
                                @else
                                <td class="text-white text-center" colspan="3">{{ $case['status'] }}</td>
                                @endif
                               
                                <td style="border-right-style: solid; border-top-right-radius: 10px;"><a href="{{ url('details/'. $case['court_id']) }}" class="btn bg-white text-orange">View Details</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                        @endisset
                    </table>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin-theme/vendor/jquery/jquery.min.js') }}"></script>
    <script>
        
        function getDisplayBoardList() {
            $.ajax({
                url: "{{ url('user-display-board/get-list') }}",
                method: "GET",
                success: function (response) {
                    if (response) {
                        var output = '';
                        var cases = response.data.cases;
                        for (var index in cases) {
                            if (cases[index]['status'] === "{{ __('In Session') }}") {
                                output += `
                                    <tr style="background-color: ${cases[index]['colour_code']}">
                                        <td class="text-white text-center" style="border-left-style: solid; border-top-left-radius: 10px;">${+index+1}</td>
                                        <td class="text-white">${cases[index]['court_number']} (${cases[index]['bench_name']})</td> 
                                        <td class="text-white">${cases[index]['item_number']}</td>
                                        <td class="text-white">${cases[index]['case_number']}</td>
                                        <td class="text-white">${cases[index]['case_title']}</td>
                                        <td style="border-right-style: solid; border-top-right-radius: 10px;"><a href="{{ url('details') }}/${cases[index]['court_id']}" class="btn bg-white text-orange">View Details</a></td>
                                    </tr> 
                                `;
                            } else {
                                output += `
                                    <tr style="background-color: ${cases[index]['colour_code']}">
                                        <td class="text-white text-center" style="border-left-style: solid; border-top-left-radius: 10px;">${+index+1}</td>
                                        <td class="text-white">${cases[index]['court_number']} (${cases[index]['bench_name']})</td> 
                                        <td class="text-white text-center" colspan="3">${cases[index]['status']}</td>
                                        <td style="border-right-style: solid; border-top-right-radius: 10px;"><a href="{{ url('details') }}/${cases[index]['court_id']}" class="btn bg-white text-orange">View Details</a></td>
                                    </tr> 
                                `;
                            }
                        }
                        $("#tbody").html(output);
                    }
                }
            });
        }

        setInterval(getDisplayBoardList, 5000);

        $("#bench_id").on("change", function() {
            var benchId = $(this).val();
            $.ajax({
                url: "{{ url('user-display-board/set-bench') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    bench_id: benchId
                },
                success: function (response) {
                    getDisplayBoardList();
                }
            });
        });
    </script>
  </body>
</html>