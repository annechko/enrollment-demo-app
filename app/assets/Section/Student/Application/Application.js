import * as React from 'react';
import {useState} from 'react';
import TextField from '@mui/material/TextField';
import Autocomplete from '@mui/material/Autocomplete';
import ClearIcon from '@mui/material/Icon';
import {
  CCard,
  CCardBody,
  CCardHeader,
  CFormInput,
  CFormLabel
} from "@coreui/react";
import AppErrorMessage from "../../../App/Common/AppErrorMessage";
import * as LoadState from "../../../App/Helper/LoadState";
import axios from "axios";

export default function Application() {
  const [schoolsRequestTimerId, setSchoolsRequestTimerId] = React.useState(null)
  const [schoolsState, setSchoolsState] = useState(LoadState.initialize())

  const [value, setValue] = React.useState(null);
  const [inputValue, setInputValue] = React.useState('');
  const [schools, setSchools] = React.useState([]);

  const schoolsUrl = window.abeApp.urls.api_student_application_school_list

  const error = null
  const coreInput = (params) => {
    return <div ref={params.InputProps.ref}>
      <CFormInput {...params.inputProps}
      />
    </div>
  }
  const muiInput = (params) => <>
    <TextField {...params} label="School name"/>
    <ClearIcon fontSize="small"/>
  </>

  const courses = [];
  const intakes = [];

  const loadSchools = (name) => {
    setSchoolsState(LoadState.startLoading())
    axios.get(schoolsUrl, {params: {'filter[name]': name}})
      .then((response) => {
        setSchoolsState(LoadState.finishLoading(response.data))
        let newSchools = [];
        if (response.data) {
          newSchools = response.data.map((s) => {
            return {label: s.name, id: s.id}
          })
        }
        setSchools(newSchools);
      })
      .catch((error) => {
        setSchoolsState(LoadState.error(error.response?.data?.error))
      })
  }

  return <>
    <CCard className="mb-4">
      <CCardHeader>
        <strong>
          Create new application
        </strong>
      </CCardHeader>
      <CCardBody>
        <AppErrorMessage error={error}/>
        <div className="mb-3">
          <CFormLabel htmlFor="school">Choose school</CFormLabel>
          <Autocomplete
            loading={schoolsRequestTimerId !== null || schoolsState.loading}
            id="school"
            sx={{width: 600}}
            filterOptions={(x) => x}
            options={schools}
            isOptionEqualToValue={(option, value) => option.title === value.title}
            value={value}
            onChange={(event, newValue) => {
              setValue(newValue);
            }}
            onInputChange={(event, newInputValue) => {
              setInputValue(newInputValue);
              if (schoolsRequestTimerId !== null) {
                clearTimeout(schoolsRequestTimerId);
              }
              setSchools([]);
              const newSchoolsRequestTimerId = setTimeout(() => {
                setSchoolsRequestTimerId(null)
                if (!newInputValue || newInputValue.length < 2) {
                  setSchools([]);
                  return;
                }
                loadSchools(newInputValue);
              }, 1000)
              setSchoolsRequestTimerId(newSchoolsRequestTimerId)
            }}
            renderInput={coreInput}
          />
        </div>
        <div className="mb-3">
          <CFormLabel htmlFor="course">Choose course</CFormLabel>
          <Autocomplete
            disabled
            disablePortal
            id="course"
            options={courses}
            sx={{width: 600}}
            renderInput={coreInput}
          />
        </div>
        <div className="mb-3">
          <CFormLabel htmlFor="intake">Choose intake</CFormLabel>
          <Autocomplete
            disabled
            disablePortal
            id="intake"
            options={intakes}
            sx={{width: 600}}
            renderInput={coreInput}
          />
        </div>
      </CCardBody>
    </CCard>
  </>
}
