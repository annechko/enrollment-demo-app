import {
  CButton,
  CFormLabel,
  CFormSelect,
  CModal,
  CModalBody,
  CModalFooter,
  CModalHeader,
  CModalTitle,
} from '@coreui/react'
import PropTypes from "prop-types";
import React, {
  useEffect,
  useState
} from 'react'
import CampusForm from "../campus/CampusForm";

const CampusOptions = ({
                         formId,
                         reload,
                         setCampusAddState,
                         isLoading,
                         campusAddState,
                         campuses,
                         setCampusValue,
                         campusValue,
                         onCampusAdd
                       }) => {
  let campusOptions = [{
    value: '...',
    label: isLoading ? 'Loading...' : '',
  }];

  campuses.forEach((item) => {
    campusOptions.push({
      value: item.id,
      label: item.name,
    })
  })
  const onChange = (e) => {
    setCampusValue(Array.from(e.currentTarget.selectedOptions).map((op) => op.value))
  }
  const [visible, setVisible] = useState(false)
  useEffect(() => {
    if (campusAddState.success === true) {
      setVisible(false)
      setCampusAddState({
        loading: false,
        error: null,
        success: false,
      })
      reload()
    }
  }, [campusAddState])

  const onCampusAddClick = (e) => {
    onCampusAdd(e)
  }
  return (
    <>
      <CFormLabel htmlFor="campusesList">Campuses</CFormLabel>
      <CButton size="sm" className="ms-1 py-0" color="primary"
        onClick={() => setVisible(!visible)}>+</CButton>
      <CModal visible={visible} onClose={() => setVisible(false)}>
        <CModalHeader onClose={() => setVisible(false)}>
          <CModalTitle>Add new campus</CModalTitle>
        </CModalHeader>
        <CModalBody>
          <CampusForm formId="campus" submitError={campusAddState.error}
            isSubmitted={campusAddState.loading}
            onSubmit={() => {
            }} showSubmitBtn={false}/>
        </CModalBody>
        <CModalFooter>
          <CButton color="secondary" onClick={() => setVisible(false)}>
            Close
          </CButton>
          <CButton color="primary" onClick={onCampusAddClick}
            disabled={campusAddState.loading}
          >Save changes</CButton>
        </CModalFooter>
      </CModal>
      <CFormSelect id="campusesList" aria-label="Choose campuses"
        value={campusValue}
        options={campusOptions}
        onChange={onChange}
        multiple
        name={formId + "[campuses][]"}
        className={isLoading ? 'app-loading' : ''}>
      </CFormSelect>
    </>
  )
}
CampusOptions.propTypes = {
  formId: PropTypes.string.isRequired,
  isLoading: PropTypes.bool,
  reload: PropTypes.func.isRequired,
  setCampusAddState: PropTypes.func.isRequired,
  onCampusAdd: PropTypes.func.isRequired,
  setCampusValue: PropTypes.func.isRequired,
  campusValue: PropTypes.oneOfType([
    PropTypes.oneOf([null]),
    PropTypes.string,
    PropTypes.arrayOf(
      PropTypes.string,
    )
  ]),
  campusAddState: PropTypes.shape({
    loading: PropTypes.bool,
    success: PropTypes.bool,
    error: PropTypes.string,
  }),
  campuses: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.string.isRequired,
      name: PropTypes.string.isRequired,
    })
  ),
}
export default CampusOptions
