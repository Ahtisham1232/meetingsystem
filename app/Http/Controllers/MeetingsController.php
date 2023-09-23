<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon; 
use App\Models\Meeting;
use App\Models\Attendee;

class MeetingsController extends Controller
{
    public function index()
    {
        $meetings = auth()->user()->meetings()->latest()->paginate(10);
        return view('meetings.index', compact('meetings'));
    }
    

public function create()
{
    return view('meetings.create');
}


    public function store(Request $request)
    {
        try {
            $request->validate([
                'subject' => 'required|string|max:255',
                'date_time' => 'required|date',
                'email_attendess_one' => 'required|email',
                'email_attendess_two' => 'required|email|different_emails:email_attendess_one',
            ], [
                'different_emails' => 'The :attribute must be different from email attendess one',
            ]);

            $meeting = new Meeting;
            $date_start = Carbon::parse($request->input('date_time'));
            $date_start->setTimezone($request->user()->timezone);
            $date_end = (clone $date_start)->addHour();
            $events = Event::create([
                'name' => $request->input('subject'),
                'startDateTime' => $date_start,
                'endDateTime' => $date_end,
                'attendees'  => [$request->input('email_attendess_one'), $request->input('email_attendess_two')]
            ]);
            $meeting->subject = $request->input('subject');
            $meeting->date_time = $request->input('date_time');
            $meeting->user_id = auth()->user()->id; 
            $meeting->eventId = $events->id;
            $meeting->save();

                $attendee = new Attendee;
                $attendee->email_attendess_one = $request->input('email_attendess_one');
                $attendee->email_attendess_two = $request->input('email_attendess_two');
                $meeting->attendees()->save($attendee);

                return redirect()->route('meetings.index')->with('success', 'Meeting created successfully!');
            } catch (\Exception $e) {
                
                return redirect()->back()->with('error', 'An error occurred while creating the meeting: ' . $e->getMessage());
            }
    }

    public function edit(Meeting $meeting)
        {
            return view('meetings.edit', compact('meeting'));
        }
        public function update(Request $request, Meeting $meeting)
        {
            try {
                $request->validate([
                    'subject' => 'required|string|max:255',
                    'date_time' => 'required|date',
                    'email_attendess_one' => 'required|email',
                    'email_attendess_two' => 'required|email|different_emails:email_attendess_one',
                ], [
                    'different_emails' => 'The :attribute must be different from email attendess one',
                ]);
        
                $meeting->subject = $request->input('subject');
                $meeting->date_time = $request->input('date_time');
        
                $googleEvent = Event::find($meeting->eventId);
                $googleEvent->name = $request->input('subject');
                $date_start = Carbon::parse($request->input('date_time'));
                $date_start->setTimezone($request->user()->timezone);
                $date_end = (clone $date_start)->addHour();
                $googleEvent->startDateTime = $date_start;
                $googleEvent->endDateTime = $date_end;
                $googleEvent->attendees = [$request->input('email_attendess_one'), $request->input('email_attendess_two')];
                $googleEvent->save();
        
                $attendee = $meeting->attendees->first();
                $attendee->email_attendess_one = $request->input('email_attendess_one');
                $attendee->email_attendess_two = $request->input('email_attendess_two');
                $attendee->save();
        
                $meeting->save();
        
                return redirect()->route('meetings.index')->with('success', 'Meeting updated successfully!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'An error occurred while updating the meeting: ' . $e->getMessage());
            }
        }
        
    public function destroy(Meeting $meeting)
        {
            $event = Event::find($meeting->eventId);
            if ($event) {
                $event->delete();
            }

            $meeting->attendees()->delete();
            $meeting->delete();

            return redirect()->route('meetings.index')->with('success', 'Meeting deleted successfully!');
        }

}
