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
import React from 'react'
import AppBackButton from "../../components/AppBackButton";
import AppErrorMessage from "../../components/AppErrorMessage";
import CampusOptions from "./CampusOptions";

const CourseForm = ({
                      onSubmit,
                      setCampusValue,
                      formId,
                      dataState,
                      campusValue,
                      isSubmitted,
                      submitError,
                      isUpdate = false
                    }) => {
  const item = dataState?.data || null
  const error = submitError || dataState?.error || null
  if (isUpdate && item === null) {
    if (error) {
      return <AppErrorMessage error={error}/>
    }
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
            {isUpdate ? 'Update course' : 'Lets create new course!'}
          </strong>
        </CCardHeader>
        <CCardBody>
          <AppErrorMessage error={error}/>
          <CForm method="post" onSubmit={onSubmit} id={formId}>
            <div className="mb-3">
              <CFormLabel htmlFor="exampleFormControlInput1">Course name</CFormLabel>
              <CFormInput
                name={formId + "[name]"}
                defaultValue={isUpdate ? item.name : ''}
                type="text"
                id="exampleFormControlInput1"
              />
            </div>
            <div className="mb-3">
              <CFormLabel htmlFor="exampleFormControlTextarea1">Course description</CFormLabel>
              <CFormTextarea id="exampleFormControlTextarea1"
                defaultValue={isUpdate ? item.description : ''}
                rows="3"
                name={formId + "[description]"}></CFormTextarea>
            </div>
            <div className="mb-3">
              <CampusOptions formId={formId}
                campusValue={campusValue === null ? item?.selectedCampuses || [] : campusValue}
                isLoading={dataState.loading}
                campuses={item?.campuses || []}
                setCampusValue={setCampusValue}/>
            </div>
            <CButton color="success"
              className={'px-4' + (isSubmitted ? ' disabled' : '')}
              disabled={isSubmitted === true}
              type="submit">
              Save
            </CButton>
          </CForm>
        </CCardBody>
      </CCard>
    </>
  )
}
CourseForm.propTypes = {
  isUpdate: PropTypes.bool,
  setCampusValue: PropTypes.func.isRequired,
  onSubmit: PropTypes.func.isRequired,
  formId: PropTypes.string.isRequired,
  isSubmitted: PropTypes.bool,
  campusValue: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.arrayOf(
      PropTypes.string.isRequired
    ),
    PropTypes.oneOf([null]),
  ]),
  submitError: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.oneOf([null]),
  ]),
  dataState: PropTypes.shape({
    data: PropTypes.shape({
      name: PropTypes.string,
      description: PropTypes.string,
      selectedCampuses: PropTypes.arrayOf(
        PropTypes.string.isRequired
      ),
      campuses: PropTypes.arrayOf(
        PropTypes.shape({
          id: PropTypes.string.isRequired,
          name: PropTypes.string.isRequired,
        })
      ).isRequired,
    }),
    loading: PropTypes.bool,
    loaded: PropTypes.bool,
    error: PropTypes.string,
  }),
}
export default CourseForm
