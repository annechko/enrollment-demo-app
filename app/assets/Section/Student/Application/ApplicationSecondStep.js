import * as React from 'react';
import {
  CFormInput,
  CFormLabel
} from "@coreui/react";

function isStepDataReady(updatedState) {
  return updatedState.passportNumber
    && updatedState.dateOfBirth
    && updatedState.fullName
    && updatedState.passportExpiry
}

export default function ApplicationSecondStep({finishStep, stepData}) {
  const [state, setState] = React.useState({});
  const addData = (fieldName, title, value) => {
    const updatedState = {...state}
    delete updatedState[fieldName]
    if (value) {
      updatedState[fieldName] = {
        formValue: value,
        value: value,
        title: title
      }
    }
    setState(updatedState)
    if (isStepDataReady(updatedState)) {
      finishStep(updatedState)
    }
  }
  return <>
    <div>
      <div className="mb-3">
        <CFormLabel htmlFor="passportNumber">Passport number</CFormLabel>
        <CFormInput
          name="passportNumber"
          type="text"
          id="passportNumber"
          onChange={(event) => {
            const passportNumber = event.target.value
            addData('passportNumber', 'Passport number', passportNumber)
          }}
        />
      </div>
      <div className="mb-3">
        <CFormLabel htmlFor="passportExpiry">Passport expiry</CFormLabel>
        <CFormInput
          name="passportExpiry"
          type="date"
          id="passportExpiry"
          onChange={(event) => {
            const passportExpiry = event.target.value
            addData('passportExpiry', 'Passport expiry', passportExpiry)
          }}
        />
      </div>
      <div className="mb-3">
        <CFormLabel htmlFor="fullName">Full name</CFormLabel>
        <CFormInput
          name="fullName"
          type="text"
          id="fullName"
          onChange={(event) => {
            const val = event.target.value
            addData('fullName', 'Full name', val)
          }}
        />
      </div>
      <div className="mb-3">
        <CFormLabel htmlFor="preferredName">Preferred name</CFormLabel>
        <CFormInput
          name="preferredName"
          type="text"
          id="preferredName"
          onChange={(event) => {
            const val = event.target.value
            addData('preferredName', 'Preferred name', val)
          }}
        />
      </div>
      <div className="mb-3">
        <CFormLabel htmlFor="dateOfBirth">Date of birth</CFormLabel>
        <CFormInput
          name="dateOfBirth"
          type="date"
          autoComplete="off"
          id="dateOfBirth"
          onChange={(event) => {
            const val = event.target.value
            addData('dateOfBirth', 'Date of birth', val)
          }}
        />
      </div>
    </div>
  </>

}
