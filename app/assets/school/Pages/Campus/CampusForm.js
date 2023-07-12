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
import AppBackButton from "../../Common/AppBackButton";
import AppErrorMessage from "../../Common/AppErrorMessage";

const CampusForm = ({
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
          <CFormLabel htmlFor="campusName">Campus name</CFormLabel>
          <CFormInput
            name={formId + "[name]"}
            defaultValue={isUpdate ? item.name : ''}
            type="text"
            id="campusName"
          />
        </div>
        <div className="mb-3">
          <CFormLabel htmlFor="campusAddress">Campus address</CFormLabel>
          <CFormTextarea id="campusAddress"
            defaultValue={isUpdate ? item.address : ''}
            rows="3"
            name={formId + "[address]"}></CFormTextarea>
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
CampusForm.propTypes = {
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
      name: PropTypes.string.isRequired,
      address: PropTypes.string
    }),
    loading: PropTypes.bool,
    loaded: PropTypes.bool,
    error: PropTypes.string,
  }),
}
export default CampusForm
