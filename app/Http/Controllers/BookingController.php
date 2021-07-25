<?php

namespace App\Http\Controllers;

use App\Http\Traits\BookingTrait;
use App\Models\Rows;
use App\Models\Seats;
use Illuminate\Http\Request;

class BookingController extends Controller
{
  use BookingTrait;
  private $rows;
  private $seats;
  function __construct(Rows $rows, Seats $seats)
  {
    $this->rows = $rows;
    $this->seats = $seats;
  }

  /*
  public function getBookings(\Illuminate\Http\Request $request) { }
  @param Illuminate\Http\Request $request
  This function return the rows with their seats 
  @return Illuminate\Http\JsonResponse
*/
  function getBookings(Request $request)
  {
    $row = $this->rows::with('seats')->get();
    return response()->json($row);
  }

  /*
  public function bookSeats(\Illuminate\Http\Request $request) { }
  @param Illuminate\Http\Request $request
  This function checks the availability of seats and reserve as requested
  @return Illuminate\Http\JsonResponse
*/
  function bookSeats(Request $request)
  {
    if ($this->findAvailableSeats() < $request->number) {
      return response()->json(["msg" => "Sorry seats are not available"]);
    }
    $seat = $this->findMaxSeatsRow($request->number);

    if (count($seat) <= 0) {
      $seat = [];
      $records = $this->searchRowsSeatsCount();
      if ($records) {
        foreach ($records as $record) {
          $seat = array_merge($seat, $record['seats']);
        }
        $seat = array_slice($seat, 0, $request->number);
        $this->markSeatBookedById($seat);
      }
    }
    return response()->json(["msg" => "Seat booked successfully", "seats" => $seat]);
  }

  /*
  public function resetSeats() { }
  @return Illuminate\Http\JsonResponse
  This function reset the bookings then reserve 10 random seats
  */
  function resetSeats()
  {
    $this->seats::where(['booked' => 1])->update(['booked' => 0]);
    $this->preFillSeats();
    return response()->json("Seats reset successfully");
  }
}
