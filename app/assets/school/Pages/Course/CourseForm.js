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
import AppBackButton from "../../Common/AppBackButton";
import AppErrorMessage from "../../Common/AppErrorMessage";
import IntakeList from "./IntakeList";

const CourseForm = ({
                      onSubmit,
                      formId,
                      dataState,
                      isSubmitted,
                      submitError,
                      isUpdate = false
                    }) => {
  const data = dataState?.data || null
  const course = data?.course || null
  const error = submitError || dataState?.error || null

  if (isUpdate && course === null) {
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
      <CCard className="mb-2">
        <CCardHeader>
          <strong>
            {isUpdate ? 'Update course' : 'Lets create new course!'}
          </strong>
        </CCardHeader>
        <CCardBody>
          <AppErrorMessage error={error}/>
          <CForm method="post" onSubmit={onSubmit} id={formId}>
            <div className="mb-2">
              <CFormLabel className="mb-0" htmlFor="courseName">Course name</CFormLabel>
              <CFormInput
                id="courseName"
                name={formId + "[name]"}
                defaultValue={isUpdate ? course.name : ''}
                type="text"
              />
            </div>
            <div className="mb-2">
              <CFormLabel className="mb-0" htmlFor="campusDescr">Course description</CFormLabel>
              <CFormTextarea id="campusDescr"
                defaultValue={isUpdate ? course.description : ''}
                rows="3"
                name={formId + "[description]"}></CFormTextarea>
            </div>
            <div>
              <CButton color="success"
                size="sm"
                className={isSubmitted ? 'disabled' : ''}
                disabled={isSubmitted === true}
                type="submit">
                {isSubmitted && <CSpinner className="me-1" component="span" size="sm" aria-hidden="true"/>}
                Save
              </CButton>
            </div>
          </CForm>
        </CCardBody>
      </CCard>
      {isUpdate && <IntakeList courseId={course.id}/>}
    </>
  )
}
CourseForm.propTypes = {
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
      course: PropTypes.shape({
        id: PropTypes.string,
        name: PropTypes.string,
        description: PropTypes.string,
      }),
    }),
    loading: PropTypes.bool,
    loaded: PropTypes.bool,
    error: PropTypes.string,
  }),
}
export default React.memo(CourseForm)
