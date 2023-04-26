import {
  CFormLabel,
  CFormSelect,
} from '@coreui/react'
import PropTypes from "prop-types";
import React from 'react'

const CampusOptions = ({formId, isLoading, campuses, setCampusValue, campusValue}) => {
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
  return (
    <>
      <CFormLabel htmlFor="campusesList">Campuses</CFormLabel>
      <CFormSelect id="campusesList" aria-label="Choose campuses"
        value={campusValue}
        options={campusOptions}
        onChange={onChange}
        multiple
        name={formId + "[campuses][]"}
        className={isLoading ? 'app-loading' : ''}>>
      </CFormSelect>
    </>
  )
}
CampusOptions.propTypes = {
  formId: PropTypes.string.isRequired,
  isLoading: PropTypes.bool,
  setCampusValue: PropTypes.func.isRequired,
  campusValue: PropTypes.oneOfType([
    PropTypes.oneOf([null]),
    PropTypes.string,
    PropTypes.arrayOf(
      PropTypes.string,
    )
  ]),
  campuses: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.string.isRequired,
      name: PropTypes.string.isRequired,
    })
  ),
}
export default CampusOptions
