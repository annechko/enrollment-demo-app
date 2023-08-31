import * as React from 'react'
import { useEffect } from 'react'
import {
  CFormInput,
  CFormLabel
} from '@coreui/react'

function isStepDataReady (updatedState) {
  return updatedState.passportNumber &&
    updatedState.dateOfBirth &&
    updatedState.fullName &&
    updatedState.passportExpiry
}

export default function ApplicationSecondStep ({ finishStep, blockStep, stepData, setStepData }) {
  useEffect(() => {
    if (isStepDataReady(stepData)) {
      finishStep()
    }
  }, [this])
  const addData = (fieldName, title, value) => {
    const updatedState = { ...stepData }
    delete updatedState[fieldName]
    if (value) {
      updatedState[fieldName] = {
        formValue: value,
        value,
        title
      }
    }
    setStepData(updatedState)
    if (isStepDataReady(updatedState)) {
      finishStep()
    } else {
      blockStep()
    }
  }

  return <>
    <div>
      <div className="mb-3">
        <CFormLabel htmlFor="passportNumber">Passport number</CFormLabel>
        <CFormInput
          defaultValue={stepData.passportNumber?.value}
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
          defaultValue={stepData.passportExpiry?.value}
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
          defaultValue={stepData.fullName?.value}
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
          defaultValue={stepData.preferredName?.value}
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
          defaultValue={stepData.dateOfBirth?.value}
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
