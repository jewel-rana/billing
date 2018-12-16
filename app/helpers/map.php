<?php

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::                                                                         :*/
/*::  This routine calculates the distance between two points (given the     :*/
/*::  latitude/longitude of those points). It is being used to calculate     :*/
/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
/*::                                                                         :*/
/*::  Definitions:                                                           :*/
/*::    South latitudes are negative, east longitudes are positive           :*/
/*::                                                                         :*/
/*::  Passed to function:                                                    :*/
/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
/*::    unit = the unit you desire for results                               :*/
/*::           where: 'M' is statute miles (default)                         :*/
/*::                  'K' is kilometers                                      :*/
/*::                  'N' is nautical miles                                  :*/
/*::  Worldwide cities and other features databases with latitude longitude  :*/
/*::  are available at https://www.geodatasource.com                          :*/
/*::                                                                         :*/
/*::  For enquiries, please contact sales@geodatasource.com                  :*/
/*::                                                                         :*/
/*::  Official Web site: https://www.geodatasource.com                        :*/
/*::                                                                         :*/
/*::         GeoDataSource.com (C) All Rights Reserved 2018                  :*/
/*::                                                                         :*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

/*
* @get distance of two location
* Params: lat1, lat2, lon1, lon2, unit (K/M/N)
* Return: distance in KM/M/N
* @Uses
* echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
* echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
* echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
*/
function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}

function toRadius( $lat, $lng, $distance = 10 )
{
  // earth's radius in km = ~6371
  $radius = 6371;

  // latitude boundaries
  $maxlat = $lat + rad2deg($distance / $radius);
  $minlat = $lat - rad2deg($distance / $radius);

  // longitude boundaries (longitude gets smaller when latitude increases)
  $maxlng = $lng + rad2deg($distance / $radius / cos(deg2rad($lat)));
  $minlng = $lng - rad2deg($distance / $radius / cos(deg2rad($lat)));

}

function minLat( $lat, $distance = 10 )
{
  // earth's radius in km = ~6371
  $radius = 6371;
  $minlat = $lat - rad2deg($distance / $radius);

  return rad2deg($distance / $radius);

  //return min lng
  // return $minlat;
}

function maxLat( $lat, $distance = 10 )
{
  // earth's radius in km = ~6371
  $radius = 6371;
  $maxlat = $lat + rad2deg($distance / $radius);

  //return min lng
  return $maxlat;
}

function minLng( $lng, $distance = 10 )
{
  // earth's radius in km = ~6371
  $radius = 6371;
  $minlng = $lng - rad2deg($distance / $radius / cos(deg2rad($lng)));

  //return min lng
  return $minlng;
}

function maxLng( $lng, $distance = 10 )
{
  // earth's radius in km = ~6371
  $radius = 6371;
  $maxlng = $lng + rad2deg($distance / $radius / cos(deg2rad($lng)));

  //return min lng
  return $maxlng;
}