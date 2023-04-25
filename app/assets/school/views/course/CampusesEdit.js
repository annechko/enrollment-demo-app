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


const CampusesEdit = () => {
  const params = useParams()
  debugger
  const campusId = params.id
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
    const loadNavItems = () => {
      setItemState({
        item: null,
        loading: true,
        loaded: false,
        error: null
      })
      const urls = window.abeApp.urls

      axios.get(urls.api.CAMPUSES_GET_ONE.replace(':id', params.id))
        .then(onLoad)
        .catch(onError)
    }
    React.useEffect(() => {
      if (!itemState.loaded && !itemState.loading && itemState.error === null) {
        loadNavItems()
      }
    }, [itemState.loaded, itemState.loading, itemState.error, params.id])
  if (itemState.loading) {
    return (<div>Loading...</div>)
  }
  const initialState = {
    loading: false,
    error: null
  }
  const [state, setState] = React.useState(initialState)

  const navigate = useNavigate();
  const onSuccess = (response) => {
    navigate(response.data?.redirect || '/');
  }
  const formName = 'campus'
  const onSubmit = (event) => {
    debugger
    submitForm({
      event,
      state,
      setState,
      formName,
      onSuccess,
      url: window.abeApp.urls.api.CAMPUSES_ADD,
      headers: {'Content-Type': 'multipart/form-data'}
    })
  }
  return (
    <>
      <Link to={window.abeApp.urls.CAMPUSES}>
        <CButton color="primary" role="button" className="mb-3">
          Go back to list
        </CButton>
      </Link>
      <br/>
      <CCard className="mb-4">
        <CCardHeader>
          <strong>Lets create new campus!</strong>
        </CCardHeader>
        <CCardBody>
          <CForm method="post" onSubmit={onSubmit} id={formName}>
            <div className="mb-3">
              <CFormLabel htmlFor="exampleFormControlInput1">Campus name</CFormLabel>
              <CFormInput
                name={formName + "[name]"}
                type="text"
                id="exampleFormControlInput1"
              />
            </div>
            <div className="mb-3">
              <CFormLabel htmlFor="exampleFormControlTextarea1">Campus address</CFormLabel>
              <CFormTextarea id="exampleFormControlTextarea1" rows="3"
                name={formName + "[address]"}></CFormTextarea>
            </div>
            <CButton color="success" className="px-4"
              disabled={state.loading}
              type="submit">
              Add
            </CButton>
          </CForm>
        </CCardBody>
      </CCard>
    </>
  )
}

export default CampusesEdit
