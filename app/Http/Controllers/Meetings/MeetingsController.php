<?php

namespace App\Http\Controllers\Meetings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Meetings\StoreMeetRequest;
use App\Http\Requests\Meetings\UpdateMeetRequest;
use App\Models\Meeting;
use App\Repositories\MeetingRepository;
use Hashids\Hashids;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use function view;


class MeetingsController extends Controller
{


    /**
     * Show the application dashboard
     */
    public function index(): View
    {
        $meetings = Meeting::orderByDesc('created_at')->paginate(12);

        return view('meetings.home' , compact('meetings'));
    }


    /**
     * Get Create Page of Meetings
     */
    public function create(): View
    {
        return view('meetings.createPage');
    }


    /**
     * @method POST
     *
     * Store Meeting in Database
     */
    public function store(StoreMeetRequest $request): RedirectResponse
    {
        $trustedData = $request->validated();
        try {
            resolve(MeetingRepository::class)->create($trustedData);
        }catch(ValidationException $e){
            return back()->with('failed' , 'Session Not Created Successfully : ' . $e->getMessage());
        }
        return redirect()->route('meetings.index')->with('success' , 'Session Created Successfully');
    }


    /**
     * @method GET
     *
     * Get Show Single Meeting
     */
    public function show(Meeting $meeting): View
    {
        return view('meetings.show' , compact('meeting'));
    }


    /**
     * @method GET
     *
     * Get Show Edit Page
     */
    public function edit(Meeting $meeting): View
    {
        return view('meetings.edit' , compact('meeting'));
    }


    /**
     * @method PUT
     *
     * Update Meeting Data in Database
     */
    public function update(Meeting $meeting , UpdateMeetRequest $request): RedirectResponse
    {
        $trustedData = $request->validated();
        try {
            resolve(MeetingRepository::class)->update($meeting, $trustedData);
        }catch (ValidationException $e) {
            return back()->with('failed' , 'Session Not Created Successfully : ' . $e->getMessage());
        }
        return redirect()->route('meetings.index')->with('success' , 'Session Updated Successfully');
    }


    /**
     * @method DELETE
     *
     * Delete one Meeting of Database
     */
    public function destroy(Meeting $meeting): RedirectResponse
    {
        $meeting->delete();
        return back()->with('success' , 'Session has been deleted');
    }


    /**
     * @method POST
     *
     * Create And Save Guest Link in Meeting Table
     */
    public function setGuestLink(Meeting $meeting): RedirectResponse
    {
        $guestLink = resolve(MeetingRepository::class)->makeGuestLink($meeting);

        $meeting->update([
            'guest_link' => $guestLink,
        ]);

        return back()->with('success' , 'Guest Link Generated');
    }


    /**
     * @method DELETE
     *
     * Remove Guest Link in Meeting Table
     */
    public function removeGuestLink(Meeting $meeting): RedirectResponse
    {
        $meeting->update([
            'guest_link' => null,
        ]);
        return back()->with('success' , 'Guest Link Deleted');
    }


}
