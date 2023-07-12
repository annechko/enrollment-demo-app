import React, {
  Component,
  Suspense
} from 'react'
import {
  BrowserRouter,
  Route,
  Routes
} from 'react-router-dom'
import './scss/style.scss'

const loading = (
  <div className="pt-3 text-center">
    <div className="sk-spinner sk-spinner-pulse"></div>
  </div>
)

const DefaultLayout = React.lazy(() => import('./Layout/DefaultLayout'))

const LoginPage = React.lazy(() => import('./Pages/Auth/LoginPage'))
const RegisterPage = React.lazy(() => import('./Pages/Auth/RegisterPage'))

class App extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const urls = window.abeApp.urls
    return (
      <BrowserRouter>
        <Suspense fallback={loading}>
          <Routes>
            <Route exact path={urls.school_login} name="Login" element={<LoginPage urls={urls}/>}/>
            <Route exact path={urls.school_register} name="Register" element={<RegisterPage urls={urls}/>}/>
            <Route path="*" name="Home" element={<DefaultLayout/>}/>
          </Routes>
        </Suspense>
      </BrowserRouter>
    )
  }
}

export default App
