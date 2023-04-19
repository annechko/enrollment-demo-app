import React, { Suspense } from 'react'
import { BrowserRouter, Route, Routes } from 'react-router-dom'
import './App.scss'
const LoginContainer = React.lazy(() => import('./LoginContainer'))
const RegisterContainer = React.lazy(() => import('./RegisterContainer'))

const loading = (
	<div className="pt-3 text-center">
		<div className="sk-spinner sk-spinner-pulse"></div>
	</div>
)
const urls = {
	login: window.abeApp.URL_LOGIN,
	register: window.abeApp.URL_REGISTER,
}

class App extends React.Component {
	render() {
		return (
			<BrowserRouter>
				<Suspense fallback={loading}>
					<Routes>
						<Route exact path={urls.login} name="Login Page" element={<LoginContainer urls={urls}/>}/>
						<Route exact path={urls.register} name="Register Page" element={<RegisterContainer urls={urls}/>}/>
					</Routes>
				</Suspense>
			</BrowserRouter>
		)
	}
}

export default App
