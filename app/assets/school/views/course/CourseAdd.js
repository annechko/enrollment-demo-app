import React, {useState} from 'react'
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
import axios from "axios";
import {useNavigate, useParams} from "react-router-dom";
import {submitForm} from "../../pages/helper/_submitForm";
import AppBackButton from "../../components/AppBackButton";

const CourseAdd = () => {
  const params = useParams()
  const navigate = useNavigate();

  const isUpdate = params.id !== undefined
  const initialState = {
    loading: false,
    error: null
  }
  const [state, setState] = useState(initialState)
  const [itemState, setItemState] = useState({
    item: null,
    loading: false,
    loaded: false,
    error: null
  })

  const onLoad = (response) => {
    setItemState({
      item: response.data,
      loading: false,
      loaded: true,
      error: null
    })
  }
  const onError = (error) => {
    setItemState({
      item: null,
      loading: false,
      loaded: false,
      error: error.response?.data?.error || 'Something went wrong'
    })
  }
  const loadItem = () => {

    const urls = window.abeApp.urls

    axios.get(urls.api_school_course.replace(':id', params.id))
      .then(onLoad)
      .catch(onError)
  }
  React.useEffect(() => {
    if (!itemState.loaded && !itemState.loading && itemState.error === null) {
      loadItem()
    }
  }, [itemState.loaded, itemState.loading, itemState.error, isUpdate, params.id])

  const onSuccess = (responseS) => {
    navigate(responseS.data?.redirect || '/');
  }
  const formId = 'course'
  const onSubmit = (event) => {
    debugger
    let url = window.abeApp.urls.api_school_course_add
    if (isUpdate) {
      url = window.abeApp.urls.api_school_course_edit.replace(':id', params.id)
    }
    submitForm({
      event,
      state,
      setState,
      formId,
      onSuccess,
      url: url,
      headers: {'Content-Type': 'multipart/form-data'}//todo should be json
    })
  }
  if (isUpdate && itemState.item === null) {
    return (<div>Loading...</div>)
  }


  return (
    <>
      <AppBackButton/>
      <br/>
      <CCard className="mb-4">
        <CCardHeader>
          <strong>
            {isUpdate ? 'Update course' : 'Lets create new course!'}
          </strong>
        </CCardHeader>
        <CCardBody>
          <CForm method="post" onSubmit={onSubmit} id={formId}>
            <div className="mb-3">
              <CFormLabel htmlFor="exampleFormControlInput1">Course name</CFormLabel>
              <CFormInput
                name={formId + "[name]"}
                defaultValue={isUpdate ? itemState.item.name : ''}
                type="text"
                id="exampleFormControlInput1"
              />
            </div>
            <div className="mb-3">
              <CFormLabel htmlFor="exampleFormControlTextarea1">Course address</CFormLabel>
              <CFormTextarea id="exampleFormControlTextarea1"
                defaultValue={isUpdate ? itemState.item.address : ''}
                rows="3"
                name={formId + "[address]"}></CFormTextarea>
            </div>
            <CButton color="success" className="px-4"
              disabled={state.loading}
              type="submit">
              Save
            </CButton>
          </CForm>
        </CCardBody>
      </CCard>
    </>
  )
}

export default CourseAdd
