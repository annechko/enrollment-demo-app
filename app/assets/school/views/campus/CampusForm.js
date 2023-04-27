import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
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

const CampusForm = ({onSubmit, formId, dataState, isSubmitted, submitError, isUpdate = false}) => {
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
      <AppBackButton/>
      <CCard className="mb-4">
        <CCardHeader>
          <strong>
            {isUpdate ? 'Update campus' : 'Lets create new campus!'}
          </strong>
        </CCardHeader>
        <CCardBody>
          <AppErrorMessage error={error}/>
          <CForm method="post" onSubmit={onSubmit} id={formId}>
            <div className="mb-3">
              <CFormLabel htmlFor="exampleFormControlInput1">Campus name</CFormLabel>
              <CFormInput
                name={formId + "[name]"}
                defaultValue={isUpdate ? item.name : ''}
                type="text"
                id="exampleFormControlInput1"
              />
            </div>
            <div className="mb-3">
              <CFormLabel htmlFor="exampleFormControlTextarea1">Campus address</CFormLabel>
              <CFormTextarea id="exampleFormControlTextarea1"
                defaultValue={isUpdate ? item.address : ''}
                rows="3"
                name={formId + "[address]"}></CFormTextarea>
            </div>
            <CButton color="success"
              className={'px-4' + (isSubmitted ? ' disabled' : '')}
              disabled={isSubmitted === true}
              type="submit">
              {isSubmitted && <CSpinner className="me-1" component="span" size="sm" aria-hidden="true"/>}
              Save
            </CButton>
          </CForm>
        </CCardBody>
      </CCard>
    </>
  )
}
CampusForm.propTypes = {
  isUpdate: PropTypes.bool,
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
