import numpy as np
import pandas as pd

class popularity_recommender_car_rental():
    def __init__(self):
        self.train_data = None
        self.user_id = None
        self.car_id = None
        self.popularity_recommendations = None
        
    def create(self, train_data, user_id, car_id):
        self.train_data = train_data
        self.user_id = user_id
        self.car_id = car_id

        train_data_grouped = train_data.groupby([self.car_id]).agg({self.user_id: 'count'}).reset_index()
        train_data_grouped.rename(columns={'user_id': 'score'}, inplace=True)

        train_data_sort = train_data_grouped.sort_values(['score', self.car_id], ascending=[0, 1])
        train_data_sort['Rank'] = train_data_sort['score'].rank(ascending=0, method='first')

        self.popularity_recommendations = train_data_sort.head(10)

    def recommend(self, user_id):
        car_recommendations = self.popularity_recommendations
        car_recommendations['user_id'] = user_id

        cols = car_recommendations.columns.tolist()
        cols = cols[-1:] + cols[:-1]
        car_recommendations = car_recommendations[cols]

        return car_recommendations


class item_similarity_recommender_car_rental():
    def __init__(self):
        self.train_data = None
        self.user_id = None
        self.car_id = None
        self.cooccurrence_matrix = None
        self.cars_dict = None
        self.rev_cars_dict = None
        self.item_similarity_recommendations = None

    def get_user_cars(self, user):
        user_data = self.train_data[self.train_data[self.user_id] == user]
        user_cars = list(user_data[self.car_id].unique())

        return user_cars

    def get_car_users(self, car):
        car_data = self.train_data[self.train_data[self.car_id] == car]
        car_users = set(car_data[self.user_id].unique())

        return car_users

    def get_all_cars_train_data(self):
        all_cars = list(self.train_data[self.car_id].unique())

        return all_cars

    def construct_cooccurrence_matrix(self, user_cars, all_cars):
        user_cars_users = []
        for i in range(0, len(user_cars)):
            user_cars_users.append(self.get_car_users(user_cars[i]))

        cooccurrence_matrix = np.matrix(np.zeros(shape=(len(user_cars), len(all_cars))), float)

        for i in range(0, len(all_cars)):
            cars_i_data = self.train_data[self.train_data[self.car_id] == all_cars[i]]
            users_i = set(cars_i_data[self.user_id].unique())

            for j in range(0, len(user_cars)):
                users_j = user_cars_users[j]
                users_intersection = users_i.intersection(users_j)

                if len(users_intersection) != 0:
                    users_union = users_i.union(users_j)
                    cooccurrence_matrix[j, i] = float(len(users_intersection)) / float(len(users_union))
                else:
                    cooccurrence_matrix[j, i] = 0

        return cooccurrence_matrix

    def generate_top_recommendations(self, user, cooccurrence_matrix, all_cars, user_cars):
        print("Non zero values in cooccurrence_matrix :%d" % np.count_nonzero(cooccurrence_matrix))

        user_sim_scores = cooccurrence_matrix.sum(axis=0) / float(cooccurrence_matrix.shape[0])
        user_sim_scores = np.array(user_sim_scores)[0].tolist()

        sort_index = sorted(((e, i) for i, e in enumerate(list(user_sim_scores))), reverse=True)

        columns = ['user_id', 'car', 'score', 'rank']
        df = pd.DataFrame(columns=columns)

        rank = 1
        for i in range(0, len(sort_index)):
            if ~np.isnan(sort_index[i][0]) and all_cars[sort_index[i][1]] not in user_cars and rank <= 10:
                df.loc[len(df)] = [user, all_cars[sort_index[i][1]], sort_index[i][0], rank]
                rank = rank + 1

        if df.shape[0] == 0:
            print("The current user has no cars for training the item similarity based recommendation model.")
            return -1
        else:
            return df

    def create(self, train_data, user_id, car_id):
        self.train_data = train_data
        self.user_id = user_id
        self.car_id = car_id

    def recommend(self, user):
        user_cars = self.get_user_cars(user)

        print("No. of unique cars for the user: %d" % len(user_cars))

        all_cars = self.get_all_cars_train_data()

        print("No. of unique cars in the training set: %d" % len(all_cars))

        cooccurrence_matrix = self.construct_cooccurrence_matrix(user_cars, all_cars)

        df_recommendations = self.generate_top_recommendations(user, cooccurrence_matrix, all_cars, user_cars)

        return df_recommendations

    def get_similar_cars(self, car_list):
        user_cars = car_list
        all_cars = self.get_all_cars_train_data()

        print("No. of unique cars in the training set: %d" % len(all_cars))

        cooccurrence_matrix = self.construct_cooccurrence_matrix(user_cars, all_cars)

        user = ""
        df_recommendations = self.generate_top_recommendations(user, cooccurrence_matrix, all_cars, user_cars)

        return df_recommendations
