import React, {useState} from 'react'
import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CCol,
  CForm,
  CFormInput,
  CFormLabel,
  CFormTextarea,
  CRow,
} from '@coreui/react'
import axios from "axios";
import {Link, useNavigate, useParams} from "react-router-dom";
import {submitForm} from "../../pages/auth/helper/_submitForm";


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

    axios.get(urls.api.COURSES_GET_ONE.replace(':id', params.id))
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
  const formName = 'course'
  const onSubmit = (event) => {
    debugger
    let url = window.abeApp.urls.api.COURSES_ADD
    if (isUpdate) {
      url = window.abeApp.urls.api.COURSES_EDIT.replace(':id', params.id)
    }
    submitForm({
      event,
      state,
      setState,
      formName,
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
      <Link to={window.abeApp.urls.COURSES}>
        <CButton color="primary" role="button" className="mb-3">
          Go back to list
        </CButton>
      </Link>
      <br/>
      <CCard className="mb-4">
        <CCardHeader>
          <strong>
            {isUpdate ? 'Update course' : 'Lets create new course!'}
          </strong>
        </CCardHeader>
        <CCardBody>
          <CForm method="post" onSubmit={onSubmit} id={formName}>
            <div className="mb-3">
              <CFormLabel htmlFor="exampleFormControlInput1">Course name</CFormLabel>
              <CFormInput
                name={formName + "[name]"}
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
                name={formName + "[address]"}></CFormTextarea>
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
