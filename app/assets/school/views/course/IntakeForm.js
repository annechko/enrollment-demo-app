import {
  CButton,
  CForm,
  CFormInput,
  CFormLabel,
  CFormTextarea,
  CSpinner,
} from '@coreui/react'
import PropTypes from "prop-types";
import React from 'react'
import AppBackButton from "../../components/AppBackButton";
import AppErrorMessage from "../../components/AppErrorMessage";

const IntakeForm = ({
                      onSubmit,
                      formId,
                      isSubmitted,
                      submitError,
                      dataState,
                      isUpdate = false,
                      showSubmitBtn = true
                    }) => {
  const item = dataState?.data || null
  const error = submitError || dataState?.error || null
  if (isUpdate && item === null) {
    return (
      <>
        <AppBackButton/>
        <div>Loading...</div>
      </>
    )
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
          <CFormTextarea id="campus"
            defaultValue={isUpdate ? item.campus : ''}
            rows="3"
            name={formId + "[campus]"}></CFormTextarea>
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
  onSubmit: PropTypes.func.isRequired,
  formId: PropTypes.string.isRequired,
  isSubmitted: PropTypes.bool,
  submitError: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.oneOf([null]),
  ]),
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
