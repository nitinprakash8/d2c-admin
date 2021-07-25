<?php

namespace App\Http\Controllers;

use App\Models\Rows;
use App\Models\Seats;
use Illuminate\Http\Request;

class BookingController extends Controller
{
  private $rows;
  private $seats;
  function __construct(Rows $rows, Seats $seats)
  {
    $this->rows = $rows;
    $this->seats = $seats;
  }

  function getBookings(Request $request)
  {
    $row = $this->rows::with('seats')->get();
    return response()->json($row);
  }

  function findMaxSeatsRow($max)
  {
    $row = $this->rows::with('seats')->get();
    $status = false;
    if ($row) {
      foreach ($row as $line) {
        $seats = $line->seats()->where('booked', 0);
        $ids = $seats->pluck('id')->toArray();

        $consecutive = $this->areConsecutives(array_values($ids), $max);
        if ($seats->count() >= $max && $consecutive) {
          $status = $this->markSeatBooked($seats->paginate($max));
          break;
        }
      }
    }
    return $status;
  }

  function areConsecutives($arr, $n)
  {
    if (!$arr) return;
    $first = $arr[0];
    for ($counter = 1; $counter <= $n; $counter++) {
      if (isset($arr[$counter]) && $arr[$counter] != $first + 1) {
        break;
        return false;
      }
    }
    return true;
  }

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

  function sortRows($a, $b)
  {
    return $a['total'] < $b['total'];
  }

  function bookSeats(Request $request)
  {
    if ($this->findAvailableSeats() < $request->number) {
      return response()->json(["msg" => "Sorry seats are not available"]);
    }
    $book = $this->findMaxSeatsRow($request->number);
    $seat = [];
    if (!$book) {
      $records = $this->searchRowsSeatsCount();
      if ($records) {
        foreach ($records as $record) {
          $seat = array_merge($seat, $record['seats']);
        }
        $this->markSeatBookedById(array_slice($seat, 0, $request->number));
      }
    }
    return response()->json(["msg" => "Seat booked successfully", "data" => $book, 'number' => $seat]);
  }

  function markSeatBooked($seats)
  {
    if ($seats) {
      foreach ($seats as $seat) {
        $this->seats->where(['id' => $seat->id])->update(['booked' => 1]);
      }
    }
    return true;
  }


  function markSeatBookedById($seats)
  {
    if ($seats) {
      $this->seats->whereIn('id', $seats)->update(['booked' => 1]);
    }
    return true;
  }

  function findAvailableSeats()
  {
    $available_seats = $this->seats->where('booked', 0);
    return $available_seats->count();
  }

  function resetSeats()
  {
    $this->seats::where(['booked' => 1])->update(['booked' => 0]);
    $this->preFillSeats();
    return response()->json("Seats reset successfully");
  }

  function preFillSeats()
  {
    $random_number_array = range(0, 80);
    shuffle($random_number_array);
    $random_number_array = array_slice($random_number_array, 0, 10);
    $this->seats::whereIn('id', $random_number_array)->update(['booked' => 1]);
    return true;
  }
}
