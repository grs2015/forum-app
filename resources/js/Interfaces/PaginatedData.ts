export interface Paginated {
    'current_page': number;
    'data': Array<TaskData>;
    'first_page_url': string;
    'from': number;
    'last_page': number;
    'last_page_url': string;
    'links': Array<LinkData>;
    'next_page_url': string | null;
    'path': string;
    'per_page': number;
    'prev_page_url': string | null;
    'to': number;
    'total': number;
}

export interface TaskData {
    id: number,
    title: string,
    description: string,
    slug: string,
}

export interface LinkData {
    'active': boolean;
    'label': string | null;
    'url': string |null;
}
