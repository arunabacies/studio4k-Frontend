@extends('layouts.app')

@section('content')

@php
$timezone = array(
    'Pacific/Midway,(-11:00)' => '(GMT-11:00) Midway Island, Samoa',
    'America/Adak,(-10:00)' => '(GMT-10:00) Hawaii-Aleutian',
    'Etc/GMT+10,(-10:00)' => '(GMT-10:00) Hawaii',
    'Pacific/Marquesas,(-09:30)' => '(GMT-09:30) Marquesas Islands',
    'Pacific/Gambier,(-09:00)' => '(GMT-09:00) Gambier Islands',
    'America/Anchorage,(-09:00)' =>  '(GMT-09:00) Alaska',
    'America/Ensenada,(-08:00)' => '(GMT-08:00) Tijuana, Baja California',
    'Etc/GMT+8,(-08:00)' => '(GMT-08:00) Pitcairn Islands',
    'America/Los_Angeles,(-08:00)' => '(GMT-08:00) Pacific Time (US & Canada)',
    'America/Denver,(-07:00)' => '(GMT-07:00) Mountain Time (US & Canada)',
    'America/Chihuahua,(-07:00)' => '(GMT-07:00) Chihuahua, La Paz, Mazatlan',
    'America/Dawson_Creek,(-07:00)' =>  '(GMT-07:00) Arizona',
    'America/Belize,(-06:00)' => '(GMT-06:00) Saskatchewan, Central America',
    'America/Cancun,(-06:00)' => '(GMT-06:00) Guadalajara, Mexico City, Monterrey',
    'Chile/EasterIsland,(-06:00)' => '(GMT-06:00) Easter Island',
    'America/Chicago,(-06:00)' => '(GMT-06:00) Central Time (US & Canada)',
    'America/New_York,(-04:00)' =>  '(GMT-04:00) Eastern Time (US & Canada)',
    'America/Havana,(-05:00)' =>  '(GMT-05:00) Cuba',
    'America/Bogota,(-05:00)' =>  '(GMT-05:00) Bogota, Lima, Quito, Rio Branco',
    'America/Caracas,(-04:30)' => '(GMT-04:30) Caracas',
    'America/Santiago,(-04:00)' => '(GMT-04:00) Santiago',
    'America/La_Paz,(-04:00)' => '(GMT-04:00) La Paz',
    'Atlantic/Stanley,(-04:00)' =>  '(GMT-04:00) Faukland Islands',
    'America/Campo_Grande,(-04:00)' =>  '(GMT-04:00) Brazil',
    'America/Goose_Bay,(-04:00)' => '(GMT-04:00) Atlantic Time (Goose Bay)',
    'America/Glace_Bay,(-04:00)' => '(GMT-04:00) Atlantic Time (Canada)',
    'America/St_Johns,(-03:30)' =>  '(GMT-03:30) Newfoundland',
    'America/Araguaina,(-03:00)' =>  '(GMT-03:00) UTC-3',
    'America/Montevideo,(-03:00)' => '(GMT-03:00) Montevideo',
    'America/Miquelon,(-03:00)' => '(GMT-03:00) Miquelon, St. Pierre',
    'America/Godthab,(-03:00)' => '(GMT-03:00) Miquelon, St. Pierre',
    'America/Argentina/Buenos_Aires,(-03:00)' => '(GMT-03:00) Buenos Aires',
    'America/Sao_Paulo,(-03:00)' => '(GMT-03:00) Brasilia',
    'America/Noronha,(-02:00)' => '(GMT-02:00) Mid-Atlantic',
    'Atlantic/Cape_Verde,(-01:00)' => '(GMT-01:00) Cape Verde Is.',
    'Atlantic/Azores,(-01:00)' => '(GMT-01:00) Azores',
    'Europe/Belfast,(-00:00)' => '(GMT) Greenwich Mean Time : Belfast',
    'Europe/Dublin,(-00:00)' => '(GMT) Greenwich Mean Time : Dublin',
    'Europe/Lisbon,(-00:00)' => ' (GMT) Greenwich Mean Time : Lisbon',
    'Europe/London,(-00:00)' => ' (GMT) Greenwich Mean Time : London',
    'Africa/Abidjan,(-00:00)' => ' (GMT) Monrovia, Reykjavik',
    'Europe/Amsterdam,(+01:00)' => '(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna',
    'Europe/Belgrade,(+01:00)' => '(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague',
    'Europe/Brussels,(+01:00)' => '(GMT+01:00) Brussels, Copenhagen, Madrid, Paris',
    'Africa/Algiers,(+01:00)' => '(GMT+01:00) West Central Africa',
    'Africa/Windhoek,(+01:00)' => '(GMT+01:00) Windhoek',
    'Asia/Beirut,(+02:00)' => '(GMT+02:00) Beirut',
    'Africa/Cairo,(+02:00)' => '(GMT+02:00) Cairo',
    'Asia/Gaza,(+02:00)' => '(GMT+02:00) Gaza',
    'Africa/Blantyre,(+02:00)' => '(GMT+02:00) Harare, Pretoria',
    'Asia/Jerusalem,(+02:00)' => '(GMT+02:00) Jerusalem',
    'Europe/Minsk,(+02:00)' => '(GMT+02:00) Minsk',
    'Asia/Damascus,(+02:00)' => '(GMT+02:00) Syria',
    'Europe/Moscow,(+03:00)' => '(GMT+03:00) Moscow, St. Petersburg, Volgograd',
    'Africa/Addis_Ababa,(+03:00)' => '(GMT+03:00) Nairobi',
    'Asia/Tehran,(+03:30)' => '(GMT+03:30) Tehran',
    'Asia/Dubai,(+04:00)' => '(GMT+04:00) Abu Dhabi, Muscat',
    'Asia/Yerevan,(+04:00)' => '(GMT+04:00) Yerevan',
    'Asia/Kabul,(+04:30)' => '(GMT+04:30) Kabul',
    'Asia/Yekaterinburg,(+05:00)' => '(GMT+05:00) Ekaterinburg',
    'Asia/Tashkent,(+05:00)' => '(GMT+05:00) Tashkent',
    'Asia/Kolkata,(+05:30)' => '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi',
    'Asia/Katmandu,(+05:45)' => '(GMT+05:45) Kathmandu',
    'Asia/Dhaka,(+06:00)' => '(GMT+06:00) Astana, Dhaka',
    'Asia/Novosibirsk,(+06:00)' => '(GMT+06:00) Novosibirsk',
    'Asia/Rangoon,(+06:30)' => '(GMT+06:30) Yangon (Rangoon)',
    'Asia/Bangkok,(+07:00)' => '(GMT+07:00) Bangkok, Hanoi, Jakarta',
    'Asia/Krasnoyarsk,(+07:00)' => '(GMT+07:00) Krasnoyarsk',
    'Asia/Hong_Kong,(+08:00)' => '(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi',
    'Asia/Irkutsk,(+08:00)' => '(GMT+08:00) Irkutsk, Ulaan Bataar',
    'Australia/Perth,(+08:00)' => '(GMT+08:00) Perth',
    'Australia/Eucla,(+08:45)' => '(GMT+08:45) Eucla',
    'Asia/Tokyo,(+09:00)' => '(GMT+09:00) Osaka, Sapporo, Tokyo',
    'Asia/Seoul,(+09:00)' => '(GMT+09:00) Seoul',
    'Asia/Yakutsk,(+09:00)' => '(GMT+09:00) Yakutsk',
    'Australia/Adelaide,(+09:30)' => '(GMT+09:30) Adelaide',
    'Australia/Darwin,(+09:30)' => '(GMT+09:30) Darwin',
    'Australia/Brisbane,(+10:00)' => '(GMT+10:00) Brisbane',
    'Australia/Hobart,(+10:00)' => '(GMT+10:00) Hobart',
    'Asia/Vladivostok,(+10:00)' => '(GMT+10:00) Vladivostok',
    'Australia/Lord_Howe,(+10:30)' => '(GMT+10:30) Lord Howe Island',
    'Etc/GMT-11,(+11:00)' => '(GMT+11:00) Solomon Is., New Caledonia',
    'Asia/Magadan,(+11:00)' => '(GMT+11:00) Magadan',
    'Pacific/Norfolk,(+11:30)' => '(GMT+11:30) Norfolk Island',
    'Asia/Anadyr,(+12:00)' => '(GMT+12:00) Anadyr, Kamchatka',
    'Pacific/Auckland,(+12:00)' => '(GMT+12:00) Auckland, Wellington',
    'Etc/GMT-12,(+12:00)' => '(GMT+12:00) Fiji, Kamchatka, Marshall Is.',
    'Pacific/Chatham,(+12:45)' => '(GMT+12:45) Chatham Islands',
    'Pacific/Tongatapu,(+13:00)' => '(GMT+13:00) Nukualofa',
    'Pacific/Kiritimati,(+14:00)' => '(GMT+14:00) Kiritimati'
);
$saved_timezone = $data ?  $data->zone.',('.$data->value. ')' : '';

@endphp

<!-- Basic card section start -->
<section id="content-types mb-1">
  <div class="row">
    <div class="col-12 mb-1">
      <div class="d-flex justify-content-between">
        <h1>Timezone settings</h1>
      </div>
      <hr/>
    </div>
  </div>
</section>
<!-- Basic Card types section end -->
          
<form method="POST" action="{{ route('edit-time-settings') }}">
@csrf
<div class ="row">
    <div class="col-md-6">
        
        <div class="form-group">
        <label>Timezone</label>
        <select class="form-control" name="timezone" >
        @foreach($timezone as $key => $value)
        <option value="{{ $key }}" {{$saved_timezone == $key ? 'selected' :''}} >{{ $value }}</option>
        @endforeach
        </select>
        </div>
    </div>
    <div class="col-md-2">
    
        <button type="submit" class="form-control btn btn-success" style="margin-top:23px;">{{ __('Submit') }}</button>

    </div>
</div>
</form>

@endsection