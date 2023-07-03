import {
  CButton,
  CCol,
  CFormInput,
  CFormLabel,
  CFormSelect,
  CRow,
} from '@coreui/react'
import PropTypes from "prop-types";
import React, {useState} from 'react'

const Intakes = ({
                   formId,
                   isLoading,
                   intakes,
                   campuses,
                 }) => {
  let campusOptions = [{
    value: '',
    label: isLoading ? 'Loading...' : 'Select a campus',
  }];

  campuses.forEach((item) => {
    campusOptions.push({
      value: item.id,
      label: item.name,
    })
  })

  const [intakesState, setIntakesState] = useState(intakes)

  const addIntake = () => {
    let updated = Array.from(intakesState)
    updated.push({
      campus: null,
      name: null,
      startDate: null,
      endDate: null,
      classSize: null,
    })
    setIntakesState(updated)
  }
  const deleteIntake = (index) => {
    let updated = Array.from(intakesState)
    updated.splice(index, 1)
    setIntakesState(updated)
  }

  let intakesItems = []

  intakesState.forEach(function (intakeData, intakeIndex) {
    intakesItems.push(
      <Intake key={intakeIndex}
        {...{campusOptions, intakeIndex, intakeData, formId, isLoading, deleteIntake}}
      />
    )
  })

  return (
    <>
      <h4 className="">Intakes</h4>
      {intakesItems}
      <div className="mb-2">
        <CButton className=""
          variant="outline" size="sm" color="primary"
          onClick={addIntake}>+ Add intake</CButton>
      </div>
    </>
  )
}
const Intake = ({campusOptions, intakeIndex, intakeData, formId, isLoading, deleteIntake}) => {
  debugger
  const namePrefix = formId + "[intakes]"
  return (
    <>
      {intakeIndex > 0 && <hr/>}
      <div className="mb-2">
        <CRow className="mb-3">
          <CCol sm={5} className="">
            <CFormLabel className="mb-0" htmlFor={'intakeName' + intakeIndex}>Intake name</CFormLabel>
            <CFormInput id={'intakeName' + intakeIndex} aria-label="Intake name"
              defaultValue={intakeData['name']}
              name={namePrefix + "[" + intakeIndex + "]" + "[name]"}
              type="text"/>
          </CCol>
          <CCol sm={2} className="">
            <CFormLabel className="mb-0" htmlFor={'classSize' + intakeIndex}>Class size</CFormLabel>
            <CFormInput id={'classSize' + intakeIndex} aria-label="Class size"
              defaultValue={intakeData['classSize']}
              name={namePrefix + "[" + intakeIndex + "]" + "[classSize]"}
              type="number"/>
          </CCol>
          <CCol className="">
            <CFormLabel className="mb-0" htmlFor={'campus' + intakeIndex}>Main campus</CFormLabel>
            <CFormSelect id={'campus' + intakeIndex} aria-label="Choose campus"
              defaultValue={intakeData['campus']}
              options={campusOptions}
              name={namePrefix + "[" + intakeIndex + "]" + "[campus]"}
              className={isLoading ? 'app-loading' : ''}>
            </CFormSelect>
          </CCol>
        </CRow>
        <CRow className="mb-3">
          <CCol sm={6} className="">
            <CFormLabel className="mb-0" htmlFor={"start" + intakeIndex.toString()}>Start</CFormLabel>
            <CFormInput
              id={"start" + intakeIndex.toString()}
              name={namePrefix + "[" + intakeIndex + "]" + "[startDate]"}
              defaultValue={intakeData['startDate']}
              type="date"
            />
          </CCol>
          <CCol sm={6} className="">
            <CFormLabel className="mb-0" htmlFor={"end" + intakeIndex.toString()}>End</CFormLabel>
            <CFormInput
              id={"end" + intakeIndex.toString()}
              name={namePrefix + "[" + intakeIndex + "]" + "[endDate]"}
              defaultValue={intakeData['endDate']}
              type="date"
            />
          </CCol>
        </CRow>
        <CRow className="mb-3">
          <div className="d-grid">
            <CButton className="mt-2" color="danger"
              variant="outline" size="sm"
              type="submit"
              onClick={(e) => {
                deleteIntake(intakeIndex)
              }}
            >
              Delete intake
            </CButton>
          </div>
        </CRow>
      </div>
    </>
  )
}
Intakes.propTypes = {
  formId: PropTypes.string.isRequired,
  isLoading: PropTypes.bool,
  intakes: PropTypes.arrayOf(
    PropTypes.shape({
      campus: PropTypes.string,
      name: PropTypes.string,
      startDate: PropTypes.string,
      endDate: PropTypes.string,
      classSize: PropTypes.number,
    })
  ),
  campuses: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.string.isRequired,
      name: PropTypes.string.isRequired,
    })
  ),
}
export default React.memo(Intakes)
