<?php

namespace App\Http\Traits;

trait BookingTrait
{
  /*
  public function findMaxSeatsRow($max) { }
  @param mixed $max is the number of seats that user wants to book
  This function reserve the seats if is available in single row
  @return array|mixed
*/
  function findMaxSeatsRow($max)
  {
    //Get all rows with seats
    $row = $this->rows::with('seats')->get();
    $status = [];
    if ($row) {
      foreach ($row as $line) {
        $seats = $line->seats()->where('booked', 0);
        if ($seats->count() >= $max) {
          //Sending the ids of seats that are going to book
          $status = $seats->paginate($max)->pluck('id')->toArray();
          $this->markSeatBookedById($status);
          break;
        }
      }
    }
    return $status; //Number of seats that have booked
  }

  /*
  public function searchRowsSeatsCount() { }
  @return array
  This function check for the maximum availability of seats in a single row and return the rows in descending order with the available seats
  */
  function searchRowsSeatsCount()
  {
    $row = $this->rows::get();
    $records = [];
    if ($row) {
      foreach ($row as $line) {
        $seats = $line->seats()->where('booked', 0);
        $records[] = ['rows' => $line, 'seats' => $seats->pluck('id')->toArray(), 'collection' => $seats, 'total' => $seats->count()];
      }
      usort($records, [$this, "sortRows"]);
    }
    return $records;
  }

  /*
  public function sortRows(
    $a,
    $b
  ) { }
  @param mixed $a
  This function sort the rows in maximum availability of seats in descending order
  @param mixed $b
  */
  function sortRows($a, $b)
  {
    return $a['total'] < $b['total'];
  }

  /*
  public function markSeatBooked($seats) { }
  @param mixed $seats This is the collection of seats model that needs to be marked as booked

  @return bool
  */
  function markSeatBookedById($seats)
  {
    if ($seats) {
      $this->seats->whereIn('id', $seats)->update(['booked' => 1]);
    }
    return true;
  }


  /*
  public function findAvailableSeats() { }
  @return mixed
  This function counts the number of available seats
  */
  function findAvailableSeats()
  {
    $available_seats = $this->seats->where('booked', 0);
    return $available_seats->count();
  }

  /*
  public function preFillSeats() { }
  @return bool
  This function reserves any 10 random seats
  */
  function preFillSeats()
  {
    $random_number_array = range(0, 80);
    shuffle($random_number_array);
    $random_number_array = array_slice($random_number_array, 0, 10);
    $this->seats::whereIn('id', $random_number_array)->update(['booked' => 1]);
    return true;
  }
}
