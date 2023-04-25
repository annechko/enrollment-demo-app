import React from 'react'
import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CForm,
  CFormInput,
  CFormLabel,
  CFormTextarea,
} from '@coreui/react'
import PropTypes from "prop-types";
import AppBackButton from "../../components/AppBackButton";

const CampusForm = ({onSubmit, formId, isLoading = false, item = null, isUpdate = false}) => {
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
            <CButton color="success" className="px-4"
              disabled={isLoading}
              type="submit">
              Save
            </CButton>
          </CForm>
        </CCardBody>
      </CCard>
    </>
  )
}
CampusForm.propTypes = {
  onSubmit: PropTypes.func.isRequired,
  formId: PropTypes.string.isRequired,
  isLoading: PropTypes.bool,
  isUpdate: PropTypes.bool,
  item: PropTypes.shape({
    name: PropTypes.string.isRequired,
    address: PropTypes.string
  })
}
export default CampusForm
