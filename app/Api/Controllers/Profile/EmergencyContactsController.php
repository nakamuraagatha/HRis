<?php

/**
 * This file is part of the HRis Software package.
 *
 * HRis - Human Resource and Payroll System
 *
 * @link    http://github.com/HB-Co/HRis
 */
namespace HRis\Api\Controllers\Profile;

use Dingo\Api\Facade\API;
use Exception;
use HRis\Api\Controllers\BaseController;
use HRis\Api\Eloquent\EmergencyContact;
use HRis\Api\Eloquent\Employee;
use HRis\Api\Requests\Profile\EmergencyContactsRequest;

class EmergencyContactsController extends BaseController
{
    /**
     * @var Employee
     */
    protected $employee;

    /**
     * @var EmergencyContact
     */
    protected $emergency_contact;

    /**
     * @param Employee         $employee
     * @param EmergencyContact $emergency_contact
     *
     * @author Bertrand Kintanar <bertrand.kintanar@gmail.com>
     */
    public function __construct(Employee $employee, EmergencyContact $emergency_contact)
    {
        $this->middleware('jwt.auth');

        $this->employee = $employee;
        $this->emergency_contact = $emergency_contact;
    }

    /**
     * Save the Profile - Emergency Contacts.
     *
     * @param EmergencyContactsRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @author Bertrand Kintanar <bertrand.kintanar@gmail.com>
     */
    public function store(EmergencyContactsRequest $request)
    {
        try {
            $emergency_contact = $this->emergency_contact->create($request->all());
        } catch (Exception $e) {
            return API::response()->array(['status' => UNABLE_ADD_MESSAGE])->statusCode(500);
        }

        return API::response()->array(['emergency_contact' => $emergency_contact, 'status' => SUCCESS_ADD_MESSAGE])->statusCode(200);
    }

    /**
     * Update the Profile - Emergency Contacts.
     *
     * @param EmergencyContactsRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @author Bertrand Kintanar <bertrand.kintanar@gmail.com>
     */
    public function update(EmergencyContactsRequest $request)
    {
        $emergency_contact = $this->emergency_contact->whereId($request->get('emergency_contact_id'))->first();

        if (!$emergency_contact) {
            return redirect()->to($request->path())->with('danger', UNABLE_RETRIEVE_MESSAGE);
        }

        try {
            $emergency_contact->update($request->all());
        } catch (Exception $e) {
            return API::response()->array(['status' => UNABLE_UPDATE_MESSAGE])->statusCode(500);
        }

        return API::response()->array(['status' => SUCCESS_UPDATE_MESSAGE])->statusCode(200);
    }

    /**
     * Delete the Profile - Emergency Contacts.
     *
     * @param EmergencyContactsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @author Bertrand Kintanar <bertrand.kintanar@gmail.com>
     */
    public function destroy(EmergencyContactsRequest $request)
    {
        $emergency_contact_id = $request->get('id');

        try {
            $this->emergency_contact->whereId($emergency_contact_id)->delete();
        } catch (Exception $e) {
            return API::response()->array(['status' => UNABLE_DELETE_MESSAGE])->statusCode(500);
        }

        return API::response()->array(['status' => SUCCESS_DELETE_MESSAGE])->statusCode(200);
    }
}