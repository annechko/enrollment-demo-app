import {
  CButton,
  CForm,
  CFormInput,
  CFormLabel,
  CFormSelect,
  CSpinner,
} from '@coreui/react'
import PropTypes from "prop-types";
import React from 'react'
import AppErrorMessage from "../../../App/Common/AppErrorMessage";
import {submitForm} from "../../../App/Helper/SubmitForm";

const IntakeForm = ({
                      formId,
                      dataState,
                      campusOptions,
                      onSuccess,
                      courseId,
                      isUpdate = false,
                      showSubmitBtn = true
                    }) => {
  const [submitState, setSubmitState] = React.useState({
    loading: false,
    error: null
  })
  const isSubmitted = submitState.loading
  const item = dataState?.data || null
  const error = submitState.error || dataState?.error || null
  if (isUpdate && item === null) {
    if (error !== null) {
      return <AppErrorMessage error={error}/>
    }
    return (
      <>
        <div>Loading...</div>
      </>
    )
  }
  const url = isUpdate
    ? window.abeApp.urls.api_school_course_intake_edit
      .replace(':courseId', courseId)
      .replace(':intakeId', item.id)
    : window.abeApp.urls.api_school_course_intake_add
      .replace(':courseId', courseId)
  const onSubmit = (event) => {
    submitForm({
      event,
      state: submitState,
      setState: setSubmitState,
      url: url,
      formId,
      onSuccess,
      headers: {'Content-Type': 'multipart/form-data'}
    })
  }
  return (
    <>
      <CForm method="post" onSubmit={onSubmit} id={formId}>
        <AppErrorMessage error={error}/>
        <div className="mb-3">
          <CFormLabel htmlFor="intakeName">Intake name</CFormLabel>
          <CFormInput
            name={formId + "[name]"}
            defaultValue={isUpdate ? item.name : ''}
            type="text"
            id="intakeName"
          />
        </div>
        <div className="mb-3">
          <CFormLabel htmlFor="startDate">Start date</CFormLabel>
          <CFormInput id="startDate"
            name={formId + "[startDate]"}
            defaultValue={isUpdate ? item.startDate : ''}
            type="date"
          />
        </div>
        <div className="mb-3">
          <CFormLabel htmlFor="endDate">End date</CFormLabel>
          <CFormInput id="endDate"
            name={formId + "[endDate]"}
            defaultValue={isUpdate ? item.endDate : ''}
            type="date"
          />
        </div>

        <div className="mb-3">
          <CFormLabel htmlFor="campus">Main campus</CFormLabel>
          <CFormSelect id="campus" aria-label="Choose campus"
            defaultValue={isUpdate ? item.campus : null}
            options={campusOptions}
            name={formId + "[campusId]"}
          >
          </CFormSelect>
        </div>
        <div className="mb-3">
          <CFormLabel htmlFor="classSize">Class size</CFormLabel>
          <CFormInput id="classSize"
            type="number"
            defaultValue={isUpdate ? item.classSize : ''}
            rows="3"
            name={formId + "[classSize]"}></CFormInput>
        </div>
        {showSubmitBtn && (
          <CButton color="success"
            className={'px-4' + (isSubmitted ? ' disabled' : '')}
            disabled={isSubmitted === true}
            type="submit">
            {isSubmitted && <CSpinner className="me-1" component="span" size="sm" aria-hidden="true"/>}
            Save
          </CButton>)
        }
      </CForm>
    </>
  )
}
IntakeForm.propTypes = {
  isUpdate: PropTypes.bool,
  showSubmitBtn: PropTypes.bool,
  courseId: PropTypes.string.isRequired,
  formId: PropTypes.string.isRequired,
  onSuccess: PropTypes.func,
  campusOptions: PropTypes.arrayOf(
    PropTypes.shape({
      value: PropTypes.string.isRequired,
      label: PropTypes.string.isRequired,
    })
  ),
  dataState: PropTypes.shape({
    data: PropTypes.shape({
      id: PropTypes.string,
      name: PropTypes.string,
      campus: PropTypes.string,
      startDate: PropTypes.string,
      endDate: PropTypes.string,
      classSize: PropTypes.number,
    }),
    loading: PropTypes.bool,
    loaded: PropTypes.bool,
    error: PropTypes.string,
  }),
}
export default IntakeForm
